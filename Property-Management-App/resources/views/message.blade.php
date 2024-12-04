<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
 
  <style>
    /* Remove the arrows from the scrollbar in WebKit browsers (Chrome, Safari) */
    #messages::-webkit-scrollbar-button {
        display: none;
    }

    /* Customize the scrollbar */
    #messages::-webkit-scrollbar {
        width: 8px; /* Adjust the width of the scrollbar */
    }

    #messages::-webkit-scrollbar-thumb {
        background: #4b5563; /* Thumb color (gray-600) */
        border-radius: 4px; /* Rounded corners for the thumb */
    }

    #messages::-webkit-scrollbar-thumb:hover {
        background: #374151; /* Thumb hover color (gray-700) */
    }

    #messages::-webkit-scrollbar-track {
        background: #f3f4f6; /* Track color (gray-100) */
    }

    /* Fallback for other browsers (non-WebKit) */
    #messages {
        scrollbar-color: #4b5563 #f3f4f6; /* Thumb color and Track color */
        scrollbar-width: thin; /* Thin scrollbar */
    }
</style>


</head>
<body class="bg-gray-100 font-sans flex justify-center items-center min-h-screen">

<div class="w-full max-w-6xl bg-white rounded-lg shadow-lg overflow-hidden h-[90vh]">
    <div class="flex h-full">
        <!-- Left Sidebar (Guest List) -->
        <div class="w-1/4 bg-green-400 p-4">
        <h2 class="text-2xl font-bold mb-6 flex items-center">
  <i class="fa-solid fa-comments mr-2"></i> Chats
</h2>

            <div id="guestList" class="space-y-4">
                <!-- Dynamically loaded guest list -->
            </div>
        </div>

        <!-- Right Chat Window -->
        <div class="w-3/4 flex flex-col">
            <!-- Chat Header -->
            <div class="flex items-center bg-gray-200 text-gray-600 p-4">
                <div class="flex items-center space-x-4">
                    <img id="guestAvatar" src="https://via.placeholder.com/50" alt="Guest" class="w-12 h-12 rounded-full hidden">
                    <span id="guestName" class="text-xl font-semibold">Select a Chat</span>
                </div>
                <button id="closeChat" 
        class="ml-auto px-4 py-2 text-red-500 bg-red-100 rounded-lg shadow-sm hover:bg-red-200 hover:text-red-600 
        focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-300 
        transition-all duration-200 text-sm font-semibold flex items-center justify-center space-x-2 hidden">
        <span><i class="fa-solid fa-right-to-bracket"></i></span>
    </button>
               
            </div>

            <!-- Messages Area -->
            <div id="messages" class="chat-box p-4 space-y-4 overflow-y-auto flex-1 bg-gray-50 custom-scrollbar">
    <div id="placeholder" class="text-gray-500 text-lg text-center">
        <p>No Chat selected. Select a Chat to view messages.</p>
    </div>
</div>

            <!-- Message Input -->
            <div id="messageInputArea" class="flex items-center p-4 bg-white border-t-2 hidden">
            <div id="messages" class="chat-box p-4 space-y-4 overflow-y-auto flex-1 bg-gray-50">
    <div id="chatDate" class="text-center text-gray-500 font-bold mb-4"></div>
</div>

<textarea id="messageInput" 
    class="w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" 
    placeholder="Type your message..." rows="1"></textarea>

                <button id="sendMessage" class="ml-3 p-3 bg-green-500 text-white rounded-md transition duration-200 hover:bg-green-500 flex items-center justify-center">
    <!-- Send Icon SVG -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l18-6-6 18-6-6-6 6V12z"/>
    </svg>
    Send
</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyBqyrDQ7_AGj190ZgAv2gduJsF8BPpse5k",
        authDomain: "messaging-web-2e28d.firebaseapp.com",
        databaseURL: "https://messaging-web-2e28d-default-rtdb.firebaseio.com",
        projectId: "messaging-web-2e28d",
        storageBucket: "messaging-web-2e28d.firebasestorage.app",
        messagingSenderId: "1030570848037",
        appId: "1:1030570848037:web:49a83b4d63760f200a74cc",
        measurementId: "G-RLCCVRSM25"
    };

    // Initialize Firebase
    const app = firebase.initializeApp(firebaseConfig);
    const db = firebase.database(app);

    const guestListDiv = document.getElementById('guestList');
    const messagesDiv = document.getElementById('messages');
    const placeholderDiv = document.getElementById('placeholder');
    const messageInputArea = document.getElementById('messageInputArea');
    const messageInput = document.getElementById('messageInput');
    const sendMessageButton = document.getElementById('sendMessage');
    const guestName = document.getElementById('guestName');
    const guestAvatar = document.getElementById('guestAvatar');
    const closeChatButton = document.getElementById('closeChat');

    let currentGuestId = null;
    let hostId = "1";
    let chatListener = null;

    // Load guest list
    function loadGuestList() {
        guestListDiv.innerHTML = ''; // Clear the guest list UI
        const hostChatsRef = db.ref(`chats/${hostId}`);

        hostChatsRef.once('value', (snapshot) => {
            const guests = snapshot.val();
            console.log('Guests data:', guests); // Log fetched data to the console

            if (guests) {
                Object.keys(guests).forEach((guestId) => {
                    const guestDiv = document.createElement('div');
                    guestDiv.className = 'p-4 bg-white rounded-lg shadow-md cursor-pointer hover:bg-green-50 hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105';

                    // Unread count logic
                    const unreadRef = db.ref(`chats/${hostId}/${guestId}/messages`);
                    unreadRef.orderByChild('read').equalTo(false).on('value', (messageSnapshot) => {
                        const unreadCount = messageSnapshot.numChildren();
                        const unreadBadge = unreadCount > 0 ? `<span class="bg-red-500 text-white rounded-full px-2 py-1 text-xs ml-2">${unreadCount}</span>` : '';
                        guestDiv.innerHTML = `<i class="fa-solid fa-user mr-2"></i>${guestId} ${unreadBadge}`;
                    });

                    guestDiv.onclick = () => loadChat(guestId);
                    guestListDiv.appendChild(guestDiv);
                });
            } else {
                const noGuestsMessage = document.createElement('div');
                noGuestsMessage.className = 'text-gray-500 text-center';
                noGuestsMessage.textContent = 'No Chats found.';
                guestListDiv.appendChild(noGuestsMessage);
            }
        }, (error) => {
            console.error('Firebase error:', error);
        });
    }

    // Load chat messages for the selected guest
    function loadChat(guestId) {
        // Remove the previous listener before adding a new one
        if (chatListener && currentGuestId) {
            const chatRefForGuest = db.ref(`chats/${hostId}/${currentGuestId}/messages`);
            chatRefForGuest.off('child_added', chatListener);
            console.log(`Listener removed for guest: ${currentGuestId}`);
        }

        currentGuestId = guestId;
        console.log(`Loading chat for guest: ${guestId}`);
        guestName.textContent = `Chat with ${guestId}`;
        guestAvatar.src = "https://www.gravatar.com/avatar?d=mp&s=200";
        guestAvatar.classList.remove('hidden');
        placeholderDiv.classList.add('hidden');
        messageInputArea.classList.remove('hidden');
        closeChatButton.classList.remove('hidden');

        // Clear current messages
        messagesDiv.innerHTML = '';
        let lastDisplayedDate = null;
        const addedMessages = new Set(); // Track added messages by their Firebase keys

        const chatRefForGuest = db.ref(`chats/${hostId}/${guestId}/messages`);

        // Mark all messages as read for this guest
        chatRefForGuest.once('value', (snapshot) => {
            snapshot.forEach((childSnapshot) => {
                childSnapshot.ref.update({ read: true });
            });
        });

        // Add a new listener for new messages
        chatListener = (snapshot) => {
            const message = snapshot.val();
            const messageKey = snapshot.key; // Firebase unique key
            console.log("New message received:", message);

            if (addedMessages.has(messageKey)) {
                console.log("Duplicate message detected, skipping.");
                return; // Skip duplicate
            }
            addedMessages.add(messageKey); // Mark message as added

            const messageDate = new Date(message.timestamp);
            const formattedDate = messageDate.toLocaleDateString('en-GB', {
                weekday: 'short',
                day: 'numeric',
                month: 'short'
            });

            if (formattedDate !== lastDisplayedDate) {
                lastDisplayedDate = formattedDate;
                const dateElement = document.createElement('div');
                dateElement.className = 'text-center text-gray-500 font-bold mb-2';
                dateElement.textContent = formattedDate;
                messagesDiv.appendChild(dateElement);
            }

            const messageElement = document.createElement('div');
            messageElement.className = `p-3 rounded-lg max-w-xs message ${message.sender === hostId ? 'bg-green-500 text-white self-end ml-auto' : 'bg-gray-200 text-gray-800 self-start'}`;
            messageElement.textContent = message.text;

            const timeElement = document.createElement('div');
            timeElement.className = 'text-xs text-gray-500 text-right mt-1';
            timeElement.textContent = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            messageElement.appendChild(timeElement);
            messagesDiv.appendChild(messageElement);

            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        };

        // Attach the listener
        chatRefForGuest.orderByChild('timestamp').limitToLast(50).on('child_added', chatListener);
        console.log(`Listener added for guest: ${guestId}`);
    }

    // Close Chat Button
    closeChatButton.addEventListener('click', () => {
        if (currentGuestId && chatListener) {
            const chatRefForGuest = db.ref(`chats/${hostId}/${currentGuestId}/messages`);
            chatRefForGuest.off('child_added', chatListener);
            console.log(`Listener removed for guest: ${currentGuestId}`);
        }
        currentGuestId = null;
        chatListener = null; // Reset listener reference
        guestName.textContent = 'Select a Chat';
        guestAvatar.src = "https://via.placeholder.com/50";
        guestAvatar.classList.add('hidden');
        placeholderDiv.classList.remove('hidden');
        messageInputArea.classList.add('hidden');
        closeChatButton.classList.add('hidden');
        messagesDiv.innerHTML = 'No Chat selected. Select a chat to view messages.';
    });

    // Send message to the selected guest
    sendMessageButton.addEventListener('click', () => {
        const messageText = messageInput.value.trim();
        if (messageText && currentGuestId) {
            const newMessage = {
                sender: hostId,
                text: messageText,
                timestamp: firebase.database.ServerValue.TIMESTAMP
            };

            db.ref(`chats/${hostId}/${currentGuestId}/messages`).push(newMessage);
            messageInput.value = '';
        }
    });

    // Initial loading of guest list
    loadGuestList();
</script>

</body>
</html>
