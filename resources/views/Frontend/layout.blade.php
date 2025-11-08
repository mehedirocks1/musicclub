<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POJ Music Club - Where Passion Meets Melody')</title>

    {{-- Tailwind & Icons (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Three.js for 3D --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    @livewireStyles

    <style>
        /* CSS for the 3D canvas background */
        #three-canvas {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; pointer-events: none;
        }
        .glass-morphism {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(234, 179, 8, 0.2);
        }
        .perspective-card { transform-style: preserve-3d; perspective: 1000px;
            transition: transform .5s cubic-bezier(.25,.46,.45,.94);}
        .perspective-card:hover { transform: rotateY(3deg) rotateX(3deg) translateZ(30px); }

        #loginBox { perspective: 1000px; transition: transform .1s ease-out; }

        .slide { opacity: 0; transition: opacity 1s ease-in-out;
            position: absolute; inset: 0; width: 100%; height: 100%; }
        .slide.active { opacity: 1; }

        .text-gradient-yellow {
            background: linear-gradient(to right, #eab308, #fde047);
            -webkit-background-clip: text; background-clip: text; color: transparent;
        }
        .text-shadow-yellow { text-shadow: 0 0 30px rgba(234,179,8,.3); }
        .text-shadow-yellow-lg { text-shadow: 0 0 40px rgba(234,179,8,.5); }
    </style>

    @stack('head')
</head>
<body class="bg-black text-white overflow-x-hidden">
    <!-- Global 3D Canvas -->
    <div id="three-canvas"></div>

    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 glass-morphism shadow-lg">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-2 rounded-lg shadow-lg transform hover:scale-110 transition-transform" style="box-shadow: 0 0 20px rgba(234, 179, 8, 0.5);">
                        <i class="fas fa-music text-black text-2xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gradient-yellow" style="text-shadow: 0 0 20px rgba(234, 179, 8, 0.3);">
                        POJ Music
                    </span>
                </div>

                <div class="hidden lg:flex items-center space-x-1">
<a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-yellow-500 font-medium hover:bg-emerald-900/50 transition-all transform hover:scale-105">Home</a>
    <a href="{{ route('about') }}" class="px-4 py-2 rounded-lg font-medium hover:text-yellow-500 hover:bg-emerald-900/50 transition-all transform hover:scale-105">About Club</a>
    <a href="{{ route('branch') }}" class="px-4 py-2 rounded-lg font-medium hover:text-yellow-500 hover:bg-emerald-900/50 transition-all transform hover:scale-105">Branch</a>
    <a href="{{ route('gallery') }}" class="px-4 py-2 rounded-lg font-medium hover:text-yellow-500 hover:bg-emerald-900/50 transition-all transform hover:scale-105">Gallery</a>
    <a href="{{ route('contact') }}" class="px-4 py-2 rounded-lg font-medium hover:text-yellow-500 hover:bg-emerald-900/50 transition-all transform hover:scale-105">Contact Us</a>
</div>
                    <a href="#" class="px-4 py-2 rounded-lg font-medium hover:text-yellow-500 hover:bg-emerald-900/50 transition-all transform hover:scale-105">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg font-medium hover:text-yellow-500 hover:bg-emerald-900/50 transition-all transform hover:scale-105">Registration</a>
                    <a href="#" class="px-4 py-2 rounded-lg bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-bold hover:from-yellow-500 hover:to-yellow-400 transition-all transform hover:scale-105 shadow-lg" style="box-shadow: 0 0 20px rgba(234, 179, 8, 0.4);">Subscribe</a>
                    <a href="{{ route('frontend.packages.index') }}" class="px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-700 to-emerald-600 text-white font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all transform hover:scale-105 shadow-lg" style="box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);">Buy Packages</a>
                </div>

                <button id="mobileMenuBtn" aria-label="Toggle mobile menu" class="lg:hidden p-2 rounded-lg hover:bg-emerald-900/50 transition-all">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <div id="mobileMenu" class="lg:hidden py-4 space-y-2 border-t border-yellow-600/30 hidden">
 <a href="{{ route('home') }}" class="block px-4 py-2 rounded-lg text-yellow-500 hover:bg-emerald-900/50">Home</a>
    <a href="{{ route('about') }}" class="block px-4 py-2 rounded-lg hover:text-yellow-500 hover:bg-emerald-900/50">About Club</a>
    <a href="{{ route('branch') }}" class="block px-4 py-2 rounded-lg hover:text-yellow-500 hover:bg-emerald-900/50">Branch</a>
    <a href="{{ route('gallery') }}" class="block px-4 py-2 rounded-lg hover:text-yellow-500 hover:bg-emerald-900/50">Gallery</a>
    <a href="{{ route('contact') }}" class="block px-4 py-2 rounded-lg hover:text-yellow-500 hover:bg-emerald-900/50">Contact Us</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:text-yellow-500 hover:bg-emerald-900/50">Login</a>
                <a href="#" class="block px-4 py-2 rounded-lg hover:text-yellow-500 hover:bg-emerald-900/50">Registration</a>
                <a href="#" class="block px-4 py-2 rounded-lg bg-gradient-to-r from-yellow-600 to-yellow-500 text-black font-semibold">Subscribe</a>
                <a href="{{ route('frontend.packages.index') }}" class="block px-4 py-2 rounded-lg bg-gradient-to-r from-emerald-700 to-emerald-600 text-white font-semibold">Buy Packages</a>
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
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-2 rounded-lg shadow-lg">
                            <i class="fas fa-music text-black text-xl"></i>
                        </div>
                        <span class="text-xl font-bold text-gradient-yellow">POJ Music</span>
                    </div>
                    <p class="text-gray-400">Where passion meets melody.</p>
                </div>
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-yellow-500">About Club</a></li>
                        <li><a href="#" class="hover:text-yellow-500">Our Branches</a></li>
                        <li><a href="#" class="hover:text-yellow-500">Gallery</a></li>
                        <li><a href="#" class="hover:text-yellow-500">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4">Membership</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-yellow-500">View Packages</a></li>
                        <li><a href="#" class="hover:text-yellow-500">Subscribe</a></li>
                        <li><a href="#" class="hover:text-yellow-500">Register</a></li>
                        <li><a href="#" class="hover:text-yellow-500">Member Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-yellow-500 font-bold mb-4">Connect With Us</h4>
                    <div class="flex space-x-3">
                        <a href="#" aria-label="Facebook" class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-400 transition-all transform hover:scale-110 shadow-lg"><i class="fab fa-facebook-f text-black"></i></a>
                        <a href="#" aria-label="Instagram" class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-400 transition-all transform hover:scale-110 shadow-lg"><i class="fab fa-instagram text-black"></i></a>
                        <a href="#" aria-label="YouTube" class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center hover:bg-yellow-400 transition-all transform hover:scale-110 shadow-lg"><i class="fab fa-youtube text-black"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-yellow-600/30 pt-8 text-center text-gray-400">
                <p>&copy; {{ now()->year }} POJ Music Club. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts

    {{-- Global Scripts for navbar etc. --}}
    <script>
        // Mobile menu
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

    {{-- Page specific scripts --}}
    @stack('scripts')
</body>
</html>