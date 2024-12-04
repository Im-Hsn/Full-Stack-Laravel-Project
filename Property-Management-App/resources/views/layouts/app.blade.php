<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Property Management App')</title>
 
    <!-- Additional head content -->
    @yield('head')
    
    <style>
        /* Custom Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes navHover {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.05);
            }
        }
        
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #4a5568;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-link:hover {
            animation: navHover 0.2s forwards;
            color: #2d3748;
        }
        
        /* Sticky Header with Shadow on Scroll */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            transition: box-shadow 0.3s ease;
        }
        
        header.scrolled {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen text-gray-800 antialiased">
    <header class="bg-white shadow-sm transition-shadow duration-300 ease-in-out">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('booking.dashboard') }}" class="text-2xl font-bold text-gray-800 transform transition-transform hover:scale-105">
                Property Management
            </a>
            
            <nav class="flex items-center space-x-6">
                @guest
                    <a href="{{ route('login') }}" class="nav-link text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md transition-all duration-300 ease-in-out">
                        Login
                    </a>
                @else
                    <div class="flex items-center space-x-6">
                        <!-- Booking Dashboard -->
                        <a href="{{ route('booking.dashboard') }}" class="nav-link text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md transition-all duration-300 ease-in-out">
                            Dashboard
                        </a>

                    <div class="flex items-center space-x-6">
                        <!-- Chat -->
                        <a href="{{ route('message') }}" class="nav-link text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md transition-all duration-300 ease-in-out">
                            Chat With Guests
                        </a>

                    <div class="flex items-center space-x-6">
                        <!-- Chat -->
                        <a href="{{ route('earnings.dashboard') }}" class="nav-link text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md transition-all duration-300 ease-in-out">
                            Track Earnings
                        </a>
                        
                        <!-- Notifications Icon -->
                        <a href="{{ route('notifications.index') }}" class="relative nav-link text-gray-600 hover:text-gray-800 px-3 py-2 rounded-md transition-all duration-300 ease-in-out group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3.001 3.001 0 01-6 0m6 0H9" />
                            </svg>
                        </a>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="nav-link text-gray-600 hover:text-red-600 px-3 py-2 rounded-md transition-all duration-300 ease-in-out">
                                Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </nav>
        </div>
    </header>

    <main class="container mx-auto flex-grow py-8 px-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md animate-fadeInDown">
                {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="bg-white shadow-inner p-6 text-center">
        <p class="text-gray-600">&copy; {{ date('Y') }} Property Management. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script>
        // Sticky Header Script
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

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
            popup.className = 'fixed top-4 right-4 bg-white border shadow-lg p-4 rounded-md z-50 animate-fadeInDown';
            popup.innerHTML = `
                <p class="mb-2">${notification.content}</p>
                <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:underline">View All Notifications</a>
            `;
            document.body.appendChild(popup);

            setTimeout(() => {
                popup.remove();
            }, 5000);
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
    @stack('scripts')
</body>
</html>