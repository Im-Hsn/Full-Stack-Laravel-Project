<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Property Management App')</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places" async defer></script>
    
    <!-- Additional head content -->
    @yield('head')
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <header class="bg-white shadow-md">
        <div class="container mx-auto p-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800">Property Management</a>
            <nav class="space-x-4 flex items-center">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-800">Sign Up</a>
                @else
                    <a href="{{ route('host.dashboard') }}" class="text-gray-600 hover:text-gray-800">Dashboard</a>
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

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white shadow p-4 text-center">
        <p class="text-gray-600">&copy; {{ date('Y') }} Property Management. All rights reserved.</p>
    </footer>

    <!-- Additional scripts -->
    @yield('scripts')
</body>
</html>
