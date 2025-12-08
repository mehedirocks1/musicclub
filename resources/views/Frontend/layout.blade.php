<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POJ Music Club - Where Passion Meets Melody')</title>

    {{-- Tailwind & Icons --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Three.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    @livewireStyles

    <style>
        /* 3D Canvas */
        #three-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; pointer-events: none;
        }

        /* Glass & card effects */
        .glass-morphism {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(234, 179, 8, 0.2);
        }
        .perspective-card { 
            transform-style: preserve-3d; perspective: 1000px;
            transition: transform .5s cubic-bezier(.25,.46,.45,.94);
        }
        .perspective-card:hover { transform: rotateY(3deg) rotateX(3deg) translateZ(30px); }
        #loginBox { perspective: 1000px; transition: transform .1s ease-out; }

        /* Slides */
        .slide { 
            opacity: 0; transition: opacity 1s ease-in-out;
            position: absolute; inset: 0; width: 100%; height: 100%; 
        }
        .slide.active { opacity: 1; }

        /* Text effects */
        .text-gradient-yellow {
            background: linear-gradient(to right, #eab308, #fde047);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .text-shadow-yellow { text-shadow: 0 0 30px rgba(234,179,8,.3); }
        .text-shadow-yellow-lg { text-shadow: 0 0 40px rgba(234,179,8,.5); }

        /* PREMIUM NAVBAR */
        .nav-item {
            @apply px-5 py-3 text-white font-semibold tracking-wide text-lg relative transition-all duration-300 ease-out;
        }
        .nav-item:hover {
            color: #facc15;
            text-shadow: 0 0 10px rgba(250, 204, 21, 0.5);
            transform: translateY(-2px);
        }
        .nav-item::after {
            content: "";
            @apply absolute left-1/2 bottom-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 ease-out transform -translate-x-1/2;
        }
        .nav-item:hover::after { width: 80%; box-shadow: 0 0 10px rgba(250, 204, 21, 0.6); }
        .nav-item.active {
            color: #facc15; font-weight: 700;
            text-shadow: 0 0 15px rgba(250, 204, 21, 0.7);
        }
        .nav-item.active::after { width: 80%; }

        /* Mobile nav */
        .mobile-nav {
            @apply block px-4 py-2 rounded-lg text-white hover:bg-emerald-900/40 hover:text-yellow-400 transition-all;
        }
        .mobile-nav.active { 
            @apply text-yellow-400 font-semibold;
        }
    </style>

    @stack('head')
</head>
<body class="bg-black text-white overflow-x-hidden">

    <!-- Global 3D Canvas -->
    <div id="three-canvas"></div>

    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-b from-black via-black/95 to-black/80 backdrop-blur-xl shadow-xl border-b border-yellow-600/20">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-24">
                <!-- LOGO -->
                <div class="flex items-center">
                    <div class="p-1 rounded-xl transition-transform hover:scale-105" style="filter: drop-shadow(0 0 10px rgba(255, 204, 0, 0.5));">
                        <img src="/idcard/POJ_music.png" alt="POJ Music Logo" class="h-14 w-auto object-contain">
                    </div>
                </div>

                <!-- DESKTOP MENU -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('about') }}" class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">About Club</a>
                    <a href="{{ route('branches') }}" class="nav-item {{ request()->routeIs('branches') ? 'active' : '' }}">Branch</a>
                    <a href="{{ route('gallery') }}" class="nav-item {{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
                    <a href="{{ route('frontend.packages.index') }}" class="nav-item {{ request()->routeIs('frontend.packages.index') ? 'active' : '' }}">Buy Course</a>
                    <a href="{{ route('contact') }}" class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a>
                    <a href="{{ route('register') }}" class="nav-item {{ request()->routeIs('register') ? 'active' : '' }}">Registration</a>
                </div>

                <!-- DONATE BUTTON -->
                <a href="#" class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-600 to-green-500 text-white font-semibold hover:from-green-500 hover:to-green-400 transition-all transform hover:scale-110 shadow-lg" style="box-shadow: 0 0 22px rgba(34, 197, 94, 0.5);">
                    Donate Us
                </a>

                <!-- MOBILE MENU BUTTON -->
                <button id="mobileMenuBtn" aria-label="Toggle mobile menu" class="lg:hidden p-3 rounded-lg hover:bg-emerald-900/40 transition-all">
                    <i class="fas fa-bars text-2xl text-white"></i>
                </button>
            </div>

            <!-- MOBILE MENU -->
            <div id="mobileMenu" class="lg:hidden py-4 space-y-2 border-t border-yellow-600/20 hidden">
                <a href="{{ route('home') }}" class="mobile-nav {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('about') }}" class="mobile-nav {{ request()->routeIs('about') ? 'active' : '' }}">About Club</a>
                <a href="{{ route('branches') }}" class="mobile-nav {{ request()->routeIs('branches') ? 'active' : '' }}">Branch</a>
                <a href="{{ route('gallery') }}" class="mobile-nav {{ request()->routeIs('gallery') ? 'active' : '' }}">Gallery</a>
                <a href="{{ route('frontend.packages.index') }}" class="mobile-nav {{ request()->routeIs('frontend.packages.index') ? 'active' : '' }}">Buy Course</a>
                <a href="{{ route('contact') }}" class="mobile-nav {{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a>
                <a href="{{ route('register') }}" class="mobile-nav {{ request()->routeIs('register') ? 'active' : '' }}">Registration</a>

                <a href="#" class="block px-4 py-2 rounded-xl bg-gradient-to-r from-green-600 to-green-500 text-white font-semibold shadow-md">
                    Donate Us
                </a>
            </div>
        </nav>
    </header>

    {{-- Page Content --}}
    <main class="pt-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="relative bg-gradient-to-t from-emerald-950 to-black border-t border-yellow-600/30 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                {{-- Column 1 --}}
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-2 rounded-lg shadow-lg">
                            <i class="fas fa-music text-black text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-gradient-yellow">POJ Music</span>
                    </div>
                    <p class="text-gray-400">Where passion meets melody.</p>
                </div>

                {{-- Column 2 --}}
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('privacy') }}" class="hover:text-yellow-500">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-yellow-500">Terms & Conditions</a></li>
                        <li><a href="{{ route('refund.policy') }}" class="hover:text-yellow-500">Refund Policy</a></li>
                    </ul>
                </div>

                {{-- Column 3 --}}
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4">Membership</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('register') }}" class="hover:text-yellow-500">Register</a></li>
                        <li><a href="#" class="hover:text-yellow-500">Member Login</a></li>
                    </ul>
                </div>

                {{-- Column 4 --}}
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4">Connect With Us</h4>
                    <div class="flex space-x-3">
                        <a href="#" aria-label="Facebook" class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-400 transition-all transform hover:scale-110 shadow-lg">
                            <i class="fab fa-facebook-f text-black"></i>
                        </a>
                        <a href="#" aria-label="YouTube" class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-400 transition-all transform hover:scale-110 shadow-lg">
                            <i class="fab fa-youtube text-black"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- SSLCOMMERZ Banner --}}
            <div class="flex justify-center mb-4">
                <img src="{{ asset('ssl.jpg') }}" alt="SSLCOMMERZ Secure Payment" class="h-16 object-contain">
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-yellow-600/30 pt-4 text-center text-gray-400">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <p>&copy; {{ now()->year }} POJ Music Club. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts

    {{-- Mobile menu toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('mobileMenu');
            if (btn && menu) {
                btn.addEventListener('click', () => {
                    const isHidden = menu.classList.toggle('hidden');
                    const icon = btn.querySelector('i');
                    icon.classList.toggle('fa-bars', isHidden);
                    icon.classList.toggle('fa-xmark', !isHidden);
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
