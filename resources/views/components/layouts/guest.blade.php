<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Digital Marketplace') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
<body class="antialiased bg-brand-black text-zinc-300 selection:bg-brand-blue selection:text-white">
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-brand-blue focus:text-white focus:rounded-lg focus:ring-2 focus:ring-brand-blue focus:ring-offset-2">
        Skip to main content
    </a>

    <!-- Header/Navigation -->
    <header class="fixed top-0 w-full z-50 glass" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white tracking-tight">
                        {{ config('app.name', 'Marketplace') }}
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-8" aria-label="Main navigation">
                    <a href="{{ route('products.search') }}" class="text-sm font-medium text-zinc-300 hover:text-white transition-colors">
                        Search
                    </a>
                    <livewire:cart.cart-icon />
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium text-zinc-300 hover:text-white transition-colors">
                        Blog
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-zinc-300 hover:text-white transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-300 hover:text-white transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium bg-brand-blue text-white rounded-full hover:bg-brand-blue-hover transition-all shadow-[0_0_20px_rgba(0,122,255,0.3)] hover:shadow-[0_0_30px_rgba(0,122,255,0.5)]">
                                Sign up
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <livewire:cart.cart-icon />
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button"
                        class="p-2 text-zinc-300 hover:text-white focus:outline-none"
                        :aria-expanded="mobileMenuOpen.toString()"
                        aria-controls="mobile-menu"
                        aria-label="Toggle navigation menu"
                    >
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <!-- Close icon -->
                        <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden bg-brand-black/95 backdrop-blur-xl border-t border-white/10" id="mobile-menu">
            <nav class="px-4 py-6 space-y-4" aria-label="Mobile navigation">
                <a href="{{ route('products.search') }}" class="block text-base font-medium text-zinc-300 hover:text-white transition-colors">
                    Search Products
                </a>
                <a href="{{ route('blog.index') }}" class="block text-base font-medium text-zinc-300 hover:text-white transition-colors">
                    Blog
                </a>
                
                @auth
                    <a href="{{ url('/dashboard') }}" class="block text-base font-medium text-zinc-300 hover:text-white transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block text-base font-medium text-zinc-300 hover:text-white transition-colors">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block w-full text-center px-5 py-3 text-base font-medium bg-brand-blue text-white rounded-full hover:bg-brand-blue-hover transition-all">
                            Get Started
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" tabindex="-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-black border-t border-white/10 pt-20 pb-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-6">{{ config('app.name', 'Marketplace') }}</h2>
                    <p class="text-zinc-400 leading-relaxed">
                        Your trusted marketplace for premium digital assets. Empowering creators worldwide.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-6">Products</h3>
                    <ul class="space-y-4 text-zinc-400">
                        <li><a href="{{ route('products.search') }}" class="hover:text-brand-blue transition-colors">All Products</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-6">Company</h3>
                    <ul class="space-y-4 text-zinc-400">
                        <li><a href="{{ route('about') }}" class="hover:text-brand-blue transition-colors">About</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-brand-blue transition-colors">Contact</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-brand-blue transition-colors">Terms</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-brand-blue transition-colors">Privacy</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-6">Support</h3>
                    <ul class="space-y-4 text-zinc-400">
                        <li><a href="{{ route('help-center') }}" class="hover:text-brand-blue transition-colors">Help Center</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-brand-blue transition-colors">FAQ</a></li>
                        <li><a href="{{ route('seller-guide') }}" class="hover:text-brand-blue transition-colors">Seller Guide</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 text-center text-zinc-500 text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Marketplace') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notifications -->
    <x-toast-container />

    @livewireScripts
</body>
</html>
