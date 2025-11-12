<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Digital Marketplace') }} - Buy and Sell Digital Assets</title>
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
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-lg hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
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
                    <a href="{{ url('/dashboard') }}" class="block px-4 py-3 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-4 py-3 text-base font-medium bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-lg hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors text-center">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content" tabindex="-1">
        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-zinc-900 to-zinc-800 dark:from-zinc-950 dark:to-zinc-900 text-white py-12 sm:py-16 md:py-20 lg:py-24" aria-labelledby="hero-heading">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 id="hero-heading" class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6">
                    Discover Premium Digital Assets
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl text-zinc-300 mb-6 sm:mb-8 px-4">
                    Buy and sell high-quality audio, video, 3D models, templates, and graphics
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                    <a href="{{ route('products.search') }}" class="px-6 sm:px-8 py-3 sm:py-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors text-center">
                        Browse Products
                    </a>
                    @guest
                        <a href="{{ route('register') }}" class="px-6 sm:px-8 py-3 sm:py-4 bg-white dark:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-900 dark:text-white font-semibold rounded-lg transition-colors text-center">
                            Start Selling
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    @php
        // Cache categories for 1 hour
        $categories = Cache::remember('homepage.categories', 3600, function () {
            return \App\Models\Category::orderBy('order')->take(6)->get();
        });
    @endphp
    
    @if($categories->count() > 0)
        <section class="py-16 bg-white dark:bg-zinc-800" aria-labelledby="categories-heading">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 id="categories-heading" class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Browse by Category
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Explore our diverse collection of digital assets
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8" role="list">
                    @foreach($categories as $category)
                        <article role="listitem" class="h-full">
                            <a href="/search?category_id={{ $category->id }}" class="group block h-full p-6 sm:p-8 bg-zinc-50 dark:bg-zinc-900 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 hover:border-green-500 dark:hover:border-green-500 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-zinc-800" aria-label="Browse {{ $category->name }} category">
                                <div class="flex flex-col items-center text-center gap-4">
                                    @if($category->icon)
                                        <div class="text-5xl sm:text-6xl group-hover:scale-110 transition-transform duration-300" aria-hidden="true">{{ $category->icon }}</div>
                                    @else
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-md" aria-hidden="true">
                                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="space-y-2">
                                        <h3 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                            {{ $category->name }}
                                        </h3>
                                        @if($category->description)
                                            <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                                {{ Str::limit($category->description, 80) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Top Selling Products -->
    @php
        // Cache top-selling products for 1 hour
        $topProducts = Cache::remember('homepage.top_products', 3600, function () {
            return \App\Models\Product::with(['category', 'user'])
                ->where('is_approved', true)
                ->where('is_active', true)
                ->orderBy('downloads_count', 'desc')
                ->take(8)
                ->get();
        });
    @endphp

    @if($topProducts->count() > 0)
        <section class="py-16 bg-zinc-50 dark:bg-zinc-900" aria-labelledby="top-products-heading">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 id="top-products-heading" class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                        Top Selling Products
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400">
                        Most popular digital assets from our marketplace
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($topProducts as $product)
                        <livewire:product.product-card :product="$product" :key="'top-product-'.$product->id" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- All Products Section -->
    <section id="products" class="py-16 bg-white dark:bg-zinc-800" aria-labelledby="latest-products-heading">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 id="latest-products-heading" class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-white mb-4">
                    Latest Products
                </h2>
                <p class="text-lg text-zinc-600 dark:text-zinc-400">
                    Discover the newest additions to our marketplace
                </p>
            </div>

            <livewire:product.product-list />
        </div>
    </section>
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-900 dark:bg-zinc-950 text-zinc-400 py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div>
                    <h2 class="text-white font-semibold mb-3 sm:mb-4">{{ config('app.name', 'Marketplace') }}</h2>
                    <p class="text-sm">
                        Your trusted marketplace for premium digital assets.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3 sm:mb-4">Products</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/search?category_id=1" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Audio</a></li>
                        <li><a href="search?category_id=2" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Video</a></li>
                        <li><a href="search?category_id=3" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">3D Models</a></li>
                        <li><a href="search?category_id=4" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Templates</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3 sm:mb-4">Company</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Terms</a></li>
                        <li><a href="#" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Privacy</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3 sm:mb-4">Support</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Help Center</a></li>
                        <li><a href="#" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-zinc-900 rounded">Seller Guide</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-zinc-800 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Marketplace') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
