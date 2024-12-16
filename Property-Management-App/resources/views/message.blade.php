@extends('layouts.app')

@section('title', 'Chat')

@push('scripts')
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-auth.js"></script>
@endpush

@section('head')
<style>
    /* Custom Chat Scrollbar */
    #messages::-webkit-scrollbar {
        width: 6px;
    }
    #messages::-webkit-scrollbar-thumb {
        background: rgba(59, 130, 246, 0.7);
        border-radius: 3px;
    }
    #messages::-webkit-scrollbar-track {
        background: rgba(59, 130, 246, 0.1);
    }

    /* Message Animations */
    @keyframes messageEnter {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-animation {
        animation: messageEnter 0.3s ease-out;
    }

    /* Guest List Item States */
    .guest-item {
        transition: all 0.3s ease;
    }
    .guest-item:hover {
        transform: translateX(5px);
        background-color: rgba(59, 130, 246, 0.1);
    }
    .guest-item.active {
        background-color: rgba(59, 130, 246, 0.2);
        border-left: 4px solid #3b82f6;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .chat-container {
            flex-direction: column;
            height: auto;
        }
        .guest-list, .chat-window {
            width: 100% !important;
        }
    }
    /* Increase font size for outgoing messages */
    .chat-box .flex.justify-end .p-3 p {
        font-size: 1.125rem !important; /* Adjust this value as needed */
    }

    /* Increase font size for incoming messages */
    .chat-box .flex.justify-start .p-3 p {
        font-size: 1.125rem !important; /* Adjust this value as needed */
    }
</style>
@endSection

@section('content')
<div class="w-full max-w-6xl bg-white rounded-xl shadow-2xl overflow-hidden h-[85vh] mx-auto">
    <div class="flex h-full chat-container">
        <!-- Left Sidebar (Guest List) -->
        <div class="w-1/4 guest-list bg-blue-500 p-4 transition-all duration-300 ease-in-out">
            <h2 class="text-2xl font-bold mb-6 flex items-center text-white">
                <i class="fa-solid fa-comments mr-3"></i> Chats
            </h2>
<!-- Search Bar -->
<div class="mb-4">
        <input 
            type="text" 
            id="guestSearch" 
            placeholder="Search guests..." 
            class="w-full px-4 py-2 rounded-lg border border-blue-200 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />
    </div>
            <div id="guestList" class="space-y-3 overflow-y-auto h-[calc(100%-100px)] custom-scrollbar pr-2">
                <!-- Dynamically loaded guest list -->
            </div>
        </div>

        <!-- Right Chat Window -->
        <div class="w-3/4 chat-window flex flex-col">
            <!-- Chat Header -->
            <div class="flex items-center bg-blue-50 text-blue-800 p-4 shadow-sm">
                <div class="flex items-center space-x-4">
                    <img id="guestAvatar" src="https://via.placeholder.com/50" alt="Guest" class="w-12 h-12 rounded-full shadow-md hidden">
                    <span id="guestName" class="text-xl font-semibold">Select a Chat</span>
                </div>
                <button id="closeChat" 
                    class="ml-auto px-4 py-2 text-blue-600 bg-blue-100 rounded-lg shadow-sm hover:bg-blue-200 
                    focus:outline-none focus:ring-2 focus:ring-blue-500 
                    transition-all duration-200 text-sm font-semibold flex items-center hidden">
                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Close Chat
                </button>
            </div>

            <!-- Messages Area -->
            <div id="messages" class="chat-box p-4 space-y-4 overflow-y-auto flex-1 bg-blue-50/50 relative">
                <div id="placeholder" class="text-blue-500 text-lg text-center absolute inset-0 flex items-center justify-center">
                    <p>No Chat selected. Select a Chat to view messages.</p>
                </div>
            </div>

            <!-- Message Input -->
            <div id="messageInputArea" class="flex items-center p-4 bg-white border-t-2 border-blue-100 hidden">
                <textarea id="messageInput" 
                    class="w-full p-3 border-2 border-blue-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none transition-all duration-300" 
                    placeholder="Type your message..." rows="1"></textarea>

                <button id="sendMessage" class="ml-3 p-3 bg-blue-500 text-white rounded-xl transition duration-300 
                    hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                    active:bg-blue-700 flex items-center justify-center group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 group-hover:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                    Send
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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

    // DOM Element References
    const guestListDiv = document.getElementById('guestList');
    const messagesDiv = document.getElementById('messages');
    const placeholderDiv = document.getElementById('placeholder');
    const messageInputArea = document.getElementById('messageInputArea');
    const messageInput = document.getElementById('messageInput');
    const sendMessageButton = document.getElementById('sendMessage');
    const guestName = document.getElementById('guestName');
    const guestAvatar = document.getElementById('guestAvatar');
    const closeChatButton = document.getElementById('closeChat');

    // State Variables
    let currentGuestId = null;
    let hostId = @json((string) auth()->user()->id);
    let chatListener = null;

    // Load Guest List
   // Load Guest List
   function loadGuestList() {
    guestListDiv.innerHTML = '';
    const hostChatsRef = db.ref(`chats/${hostId}`);
    const searchInput = document.getElementById('guestSearch');
    let allGuests = []; // Local storage for guest data

    hostChatsRef.once('value', (snapshot) => {
        const guests = snapshot.val();

        if (guests) {
            const guestEntries = Object.entries(guests);

            // Pre-fetch all guest names and store them locally
            Promise.all(
                guestEntries.map(([guestId]) =>
                    db.ref(`chats/${hostId}/${guestId}/guest_info`).once('value').then((infoSnapshot) => ({
                        guestId,
                        name: infoSnapshot.val()?.name || `Guest ${guestId}`,
                    }))
                )
            ).then((guestData) => {
                allGuests = guestData; // Store all guest data locally
                renderGuestList(allGuests); // Initial render
            });

            // Attach search functionality
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                const filteredGuests = allGuests.filter((guest) =>
                    guest.name.toLowerCase().includes(query)
                );
                renderGuestList(filteredGuests);
            });
        } else {
            const noGuestsMessage = document.createElement('div');
            noGuestsMessage.className = 'text-white text-center p-4';
            noGuestsMessage.textContent = 'No active chats';
            guestListDiv.appendChild(noGuestsMessage);
        }
    }, (error) => {
        console.error('Firebase error:', error);
    });

    function renderGuestList(guestData) {
        guestListDiv.innerHTML = ''; // Clear previous content

        guestData.forEach(({ guestId, name }) => {
            const guestDiv = document.createElement('div');
            guestDiv.className = 'guest-item p-3 rounded-lg cursor-pointer flex items-center transition-all group bg-white hover:bg-blue-50 shadow-sm';

            // Unread count logic
            const unreadRef = db.ref(`chats/${hostId}/${guestId}/messages`);
            unreadRef.orderByChild('read').equalTo(false).on('value', (messageSnapshot) => {
                const unreadCount = messageSnapshot.numChildren();
                const unreadBadge = unreadCount > 0
                    ? `<span class="bg-red-500 text-white rounded-full px-2 py-1 text-xs ml-2">${unreadCount}</span>`
                    : '';

                guestDiv.innerHTML = `
                    <img src="https://www.gravatar.com/avatar/${guestId}?d=mp&s=50" class="w-10 h-10 rounded-full mr-3 group-hover:scale-110 transition-transform">
                    <div class="flex-1">
                        <p class="font-semibold text-blue-800">${name}</p>
                    </div>
                    ${unreadBadge}
                `;
            });

            guestDiv.onclick = () => loadChat(guestId, name);
            guestListDiv.appendChild(guestDiv);
        });
    }
}


// Load Chat for a Specific Guest
function loadChat(guestId, guestDisplayName) {
    // Remove previous listener if exists
    if (chatListener && currentGuestId) {
        const chatRefForGuest = db.ref(`chats/${hostId}/${currentGuestId}/messages`);
        chatRefForGuest.off('child_added', chatListener);
    }

    // Reset UI and set current guest
    currentGuestId = guestId;
    guestName.textContent = `Chat with ${guestDisplayName}`;
    guestAvatar.src = `https://www.gravatar.com/avatar/${guestId}?d=mp&s=200`;
    guestAvatar.classList.remove('hidden');
    placeholderDiv.classList.add('hidden');
    messageInputArea.classList.remove('hidden');
    closeChatButton.classList.remove('hidden');

    // Clear current messages
    messagesDiv.innerHTML = '';
    let lastDisplayedDate = null;
    const addedMessages = new Set();

    const chatRefForGuest = db.ref(`chats/${hostId}/${guestId}/messages`);

    // Mark all messages as read
    chatRefForGuest.once('value', (snapshot) => {
        snapshot.forEach((childSnapshot) => {
            childSnapshot.ref.update({ read: true });
        });
    });

    // Listener for new messages
    chatListener = (snapshot) => {
        const message = snapshot.val();
        const messageKey = snapshot.key;

        if (addedMessages.has(messageKey)) return;
        addedMessages.add(messageKey);

        const messageDate = new Date(message.timestamp);
        const formattedDate = messageDate.toLocaleDateString('en-GB', {
            weekday: 'short',
            day: 'numeric',
            month: 'short'
        });

        // Add date separator if changed
        if (formattedDate !== lastDisplayedDate) {
            lastDisplayedDate = formattedDate;
            const dateElement = document.createElement('div');
            dateElement.className = 'text-center text-gray-500 font-bold mb-2';
            dateElement.textContent = formattedDate;
            messagesDiv.appendChild(dateElement);
        }

        // Create message element
        const messageElement = document.createElement('div');
        messageElement.className = `flex ${message.sender === hostId ? 'justify-end' : 'justify-start'} mb-2`;
        
        const contentElement = document.createElement('div');
        contentElement.className = `p-3 rounded-xl max-w-xs ${
            message.sender === hostId 
                ? 'bg-blue-500 text-white' 
                : 'bg-gray-200 text-gray-800'
        }`;
        
        contentElement.innerHTML = `
            <p class="text-sm">${message.text}</p>
            <span class="text-xs ${
                message.sender === hostId 
                    ? 'text-blue-100' 
                    : 'text-gray-500'
            } block mt-1 text-right">
                ${messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
            </span>
        `;

        messageElement.appendChild(contentElement);
        messagesDiv.appendChild(messageElement);

        // Auto-scroll to bottom
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    };

    // Attach the listener
    chatRefForGuest.orderByChild('timestamp').limitToLast(50).on('child_added', chatListener);
}


    // Close Chat Functionality
    closeChatButton.addEventListener('click', () => {
        if (currentGuestId && chatListener) {
            const chatRefForGuest = db.ref(`chats/${hostId}/${currentGuestId}/messages`);
            chatRefForGuest.off('child_added', chatListener);
        }
        
        // Reset UI
        currentGuestId = null;
        chatListener = null;
        guestName.textContent = 'Select a Chat';
        guestAvatar.src = "https://via.placeholder.com/50";
        guestAvatar.classList.add('hidden');
        placeholderDiv.classList.remove('hidden');
        messageInputArea.classList.add('hidden');
        closeChatButton.classList.add('hidden');
        messagesDiv.innerHTML = '';
    });

    // Send Message Functionality
    sendMessageButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const messageText = messageInput.value.trim();
        if (messageText && currentGuestId) {
            const newMessage = {
                sender: hostId,
                text: messageText,
                timestamp: firebase.database.ServerValue.TIMESTAMP,
                
            };

            db.ref(`chats/${hostId}/${currentGuestId}/messages`).push(newMessage);
            messageInput.value = '';
        }
    }

    // Initialize guest list on page load
    loadGuestList();
</script>
@endpush