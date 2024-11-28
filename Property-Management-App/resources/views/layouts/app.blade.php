<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Property Management App')</title>

<!-- Additional head content -->
@yield('head')
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <header class="bg-white shadow-md">
        <div class="container mx-auto p-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-gray-800">Property Management</a>
            <nav class="space-x-4 flex items-center">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">Login</a>
                @else
                    <!-- Booking Dashboard -->
                    <a href="{{ route('booking.dashboard') }}" class="text-gray-600 hover:text-gray-800">Booking Dashboard</a>
                    
                    <!-- Chat -->
                    
                    
                    <!-- Notifications Icon -->
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-600 hover:text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3.001 3.001 0 01-6 0m6 0H9" />
                        </svg>
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                    </form>
                @endguest
            </nav>
        </div>
    </header>

    <main class="container mx-auto flex-grow py-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="bg-white shadow p-4 text-center">
        <p class="text-gray-600">&copy; {{ date('Y') }} Property Management. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script>
        
        async function fetchUnreadNotifications() {
            
            try {
                
                const response = await fetch('/notifications/unread');
                const notifications = await response.json();

                notifications.forEach(notification => {
                    showNotificationPopup(notification);
                });
              
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
           
        }


        function showNotificationPopup(notification) {
            const popup = document.createElement('div');
            popup.className = 'fixed top-4 right-4 bg-white border shadow-lg p-4 rounded-md z-50';
            popup.innerHTML = `
                <p>${notification.content}</p>
                <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:underline">View All Notifications</a>
            `;
            document.body.appendChild(popup);

            setTimeout(() => {
                popup.remove();
            }, 5000); // Remove the popup after 5 seconds
            
        }

        // Fetch unread notifications on page load
        document.addEventListener('DOMContentLoaded', fetchUnreadNotifications);
        
    </script>

    <!-- Google Maps API (Check if key is present) -->
    <script>
        var googleMapsApiKey = "{{ env('GOOGLE_MAPS_API_KEY') }}";
        if (googleMapsApiKey) {
            var script = document.createElement('script');
            script.src = "https://maps.googleapis.com/maps/api/js?key=" + googleMapsApiKey + "&libraries=places";
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        } else {
            console.log("Google Maps API key is missing. Map cannot be loaded.");
        }
    </script>
    <!-- Tailwind CSS (via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Additional scripts -->
    @stack('scripts')
</body>
</html>