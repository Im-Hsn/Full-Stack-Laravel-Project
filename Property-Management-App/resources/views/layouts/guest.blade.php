<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guest Portal')</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @yield('head')
    <style>
        /* Modern, Sleek Styling */
        body.guest-layout {
            background: linear-gradient(135deg, #f0f9ff, #cbebff, #a6dfff);
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .guest-layout header {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(209, 213, 219, 0.25);
            transition: all 0.3s ease;
        }

        .guest-layout header .nav-link {
            color: #1f2937;
            position: relative;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .guest-layout header .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: linear-gradient(to right, #10b981, #059669);
            transition: width 0.3s ease;
        }

        .guest-layout header .nav-link:hover::after {
            width: 100%;
        }

        header.scrolled {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        footer {
            background: linear-gradient(to right, #f0f9ff, #cbebff);
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="guest-layout antialiased min-h-screen flex flex-col">
    <header class="shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('guest.properties') }}" class="text-2xl font-bold text-black bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-600">Guest Portal</a>
            <nav class="flex items-center space-x-6">
                <a href="{{ route('guest.properties') }}" class="nav-link">Available Properties</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="nav-link">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container mx-auto flex-grow py-8 px-4 flex-1">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="text-gray-600 py-4 text-center shadow-inner">
        <p class="text-sm opacity-75">&copy; {{ date('Y') }} Guest Portal. All rights reserved.</p>
    </footer>

    <script>
        // Sticky Header on Scroll
        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>