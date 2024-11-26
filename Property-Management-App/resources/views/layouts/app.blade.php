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
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">Dashboard</a>
                    <a href="{{ route('chat') }}" class="text-gray-600 hover:text-gray-800">Chat</a>
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
    <!-- Google Maps API (Check if key is present) -->
    <script>
        var googleMapsApiKey = "{{ env('GOOGLE_MAPS_API_KEY') }}";
        if (googleMapsApiKey) {
            // Load the Google Maps API with the provided key
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