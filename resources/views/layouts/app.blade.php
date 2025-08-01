<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IR Real Estates') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- AOS Animate On Scroll CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <header class="bg-white shadow py-2 px-6 fixed top-0 w-full z-50">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-auto">
                    <span class="text-xl font-bold text-gray-800">IR Real Estates</span>
                </div>
                <nav class="space-x-6 text-sm font-medium text-gray-600">
                    <a href="/" class="hover:text-indigo-600">Home</a>
                    <a href="{{ url('/properties') }}" class="hover:text-indigo-600">Properties</a>
                    <a href="{{ url('/sellers') }}" class="hover:text-indigo-600">Sellers</a>
                    <a href="{{ url('/contact') }}" class="hover:text-indigo-600">Contact</a>
                    @guest
                        <a href="{{ route('login') }}" class="hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="hover:text-indigo-600">Register</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                    @endguest
                </nav>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-200 py-4 px-6 fixed bottom-0 w-full z-50">
            <div class="max-w-7xl mx-auto text-center">
                <p>&copy; {{ date('Y') }} IR Real Estates. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- AOS Animate On Scroll JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: false,
        });
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                speed: 50,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>
</body>

</html>
