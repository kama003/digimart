<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
<body class="antialiased bg-zinc-50 dark:bg-zinc-900">
    <!-- Skip to main content link for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-green-600 focus:text-white focus:rounded-lg focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
        Skip to main content
    </a>

    <!-- Header/Navigation -->
    <header class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white">
                        {{ config('app.name', 'Marketplace') }}
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-2 lg:gap-4" aria-label="Main navigation">
                    <a href="{{ route('products.search') }}" class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors">
                        Search
                    </a>
                    
                    <!-- Cart Icon -->
                    <livewire:cart.cart-icon />
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                            Sign Up
                        </a>
                    @endauth
                </nav>

                <!-- Mobile Menu Button and Cart -->
                <div class="flex md:hidden items-center gap-2">
                    <livewire:cart.cart-icon />
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button"
                        class="p-3 inline-flex items-center justify-center text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 rounded-lg"
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
        <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden border-t border-zinc-200 dark:border-zinc-700" id="mobile-menu">
            <nav class="px-4 py-4 space-y-2" aria-label="Mobile navigation">
                <a href="{{ route('products.search') }}" class="block px-4 py-3 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                    Search Products
                </a>
                
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 text-base font-medium bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-center">
                        Sign Up
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" tabindex="-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 mt-12 sm:mt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <div class="text-center text-sm text-zinc-600 dark:text-zinc-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Digital Marketplace') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notifications -->
    <x-toast-container />

    @livewireScripts
</body>
</html>
