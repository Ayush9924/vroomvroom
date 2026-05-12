<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VRooMVRooM - Luxury Car Rental</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-black': '#0a0a0a',
                        'brand-white': '#f5f5f0',
                        'brand-accent': '#3B6FFF',
                        'brand-gray-light': '#f0f0ec',
                        'brand-gray-mid': '#888888',
                        'brand-card': '#ffffff',
                    },
                    fontFamily: {
                        'display': ['"Bebas Neue"', 'sans-serif'],
                        'body': ['"Syne"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Three.js Implementation (Removed) -->
    <!-- (If it were here, it would be included here based on CDN instructions) -->

    <!-- GSAP Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        :root {
            --black: #0a0a0a;
            --white: #f5f5f0;
            --accent: #3B6FFF;
            --accent-glow: rgba(59, 111, 255, 0.3);
            --gray-light: #f0f0ec;
            --gray-mid: #888888;
            --card-bg: #ffffff;
            --dark-card: #111111;
        }
        body {
            font-family: 'Syne', sans-serif;
            background-color: var(--gray-light);
            color: var(--black);
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        #hero-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none; /* Allows mouse events to pass through for parallax */
        }
        .glass-nav {
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.1);
        }
        .btn-primary {
            background-color: var(--accent);
            color: white;
            border-radius: 9999px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            box-shadow: 0 0 20px var(--accent-glow);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="antialiased selection:bg-brand-accent selection:text-white">

    <!-- Navbar -->
    <nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 50) ? true : false"
         :class="{ 'glass-nav border-b border-black/10': scrolled, 'bg-transparent': !scrolled && window.pageYOffset <= 50, 'bg-white/80 backdrop-blur-md': scrolled }"
         class="fixed w-full z-50 transition-all duration-300 py-4 top-0 text-brand-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="flex items-center gap-3 z-50">
                    <div class="w-10 h-10 rounded-full bg-brand-black flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="font-display text-2xl tracking-wider text-brand-black">VRooMVRooM</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ url('/') }}" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Home</a>
                    <a href="{{ route('categories.index') }}" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Browse Cars</a>
                    <a href="#services" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Services</a>
                    <a href="#about" class="font-medium hover:text-brand-accent transition-colors text-brand-black">About Us</a>
                    <a href="#benefits" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Benefits</a>
                    <a href="#solutions" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Our Solutions</a>
                </div>

                <!-- Auth / Contact -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Logout</button>
                        </form>
                    @else
                        @if(Route::has('login'))
                            <a href="{{ route('login') }}" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Login</a>
                        @endif
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="font-medium hover:text-brand-accent transition-colors text-brand-black">Register</a>
                        @endif
                        <a href="#contact" class="bg-brand-black text-white hover:bg-black px-6 py-2.5 rounded-full font-semibold text-sm tracking-wide shadow-lg transition-all">Contact Us</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center z-50">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="focus:outline-none transition-colors text-brand-black">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-full"
             class="absolute top-0 left-0 w-full h-screen bg-brand-white z-40 flex flex-col items-center justify-center space-y-8" x-cloak>
            <a href="#" @click="mobileMenuOpen = false" class="text-2xl font-display text-brand-black hover:text-brand-accent">Home</a>
            <a href="#services" @click="mobileMenuOpen = false" class="text-2xl font-display text-brand-black hover:text-brand-accent">Services</a>
            <a href="#about" @click="mobileMenuOpen = false" class="text-2xl font-display text-brand-black hover:text-brand-accent">About Us</a>
            <a href="#benefits" @click="mobileMenuOpen = false" class="text-2xl font-display text-brand-black hover:text-brand-accent">Benefits</a>
            @auth
                <a href="{{ url('/dashboard') }}" @click="mobileMenuOpen = false" class="text-2xl font-display text-brand-black hover:text-brand-accent">Dashboard</a>
            @else
                @if(Route::has('login'))
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="text-2xl font-display text-brand-black hover:text-brand-accent">Login</a>
                @endif
            @endauth
            <a href="#contact" @click="mobileMenuOpen = false" class="bg-brand-black text-white px-8 py-3 rounded-full text-lg font-semibold mt-4">Contact Us</a>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-brand-gray-light py-12 border-t border-brand-gray-mid/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-8">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand-accent flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="font-display text-2xl tracking-wider text-brand-black">VRooMVRooM</span>
                </div>

                <!-- Nav -->
                <div class="flex flex-wrap justify-center gap-6 md:gap-8">
                    <a href="#" class="font-medium text-brand-black hover:text-brand-accent transition-colors">Home</a>
                    <a href="#services" class="font-medium text-brand-black hover:text-brand-accent transition-colors">Services</a>
                    <a href="#about" class="font-medium text-brand-black hover:text-brand-accent transition-colors">About Us</a>
                    <a href="#benefits" class="font-medium text-brand-black hover:text-brand-accent transition-colors">Benefits</a>
                    <a href="#solutions" class="font-medium text-brand-black hover:text-brand-accent transition-colors">Our Solutions</a>
                </div>

                <!-- Contact -->
                <a href="#contact" class="btn-primary px-8 py-3 font-semibold">Contact Us</a>
            </div>

            <div class="text-center pt-8 border-t border-brand-gray-mid/20">
                <p class="text-brand-gray-mid text-sm">© 2026 VRooMVRooM. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
