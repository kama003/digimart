<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
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
    <main id="main-content" tabindex="-1" class="pt-20">
        <!-- Hero Section -->
        <section class="relative overflow-hidden min-h-[80vh] flex items-center justify-center" aria-labelledby="hero-heading">
            <!-- Abstract Background -->
            <div class="absolute inset-0 z-0">
                <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] bg-brand-blue/20 rounded-full blur-[120px] mix-blend-screen animate-pulse"></div>
                <div class="absolute bottom-[-20%] right-[-10%] w-[600px] h-[600px] bg-brand-purple/20 rounded-full blur-[120px] mix-blend-screen animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute top-[30%] left-[50%] transform -translate-x-1/2 w-[400px] h-[400px] bg-white/5 rounded-full blur-[100px]"></div>
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="max-w-5xl mx-auto text-center">
                    <h1 id="hero-heading" class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-bold mb-8 tracking-tighter leading-tight">
                        Discover Premium <br class="hidden sm:block" />
                        <span class="text-gradient-blue">Digital Assets.</span>
                    </h1>
                    <p class="text-xl sm:text-2xl text-zinc-400 mb-10 max-w-3xl mx-auto leading-relaxed">
                        Streamline operations and scale your digital presence with our premium collection of automation tools and assets.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('products.search') }}" class="px-8 py-4 bg-brand-blue hover:bg-brand-blue-hover text-white text-lg font-semibold rounded-full transition-all shadow-[0_0_20px_rgba(0,122,255,0.4)] hover:shadow-[0_0_40px_rgba(0,122,255,0.6)] hover:-translate-y-1">
                            Browse Products
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white text-lg font-semibold rounded-full transition-all border border-white/10 hover:border-white/20">
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
            <section class="py-24 relative z-10" aria-labelledby="categories-heading">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 id="categories-heading" class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6">
                            Browse by Category
                        </h2>
                        <p class="text-lg text-zinc-400">
                            Explore our diverse collection of digital assets
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" role="list">
                        @foreach($categories as $category)
                            <article role="listitem" class="h-full">
                                <a href="{{ route('category.show', $category->slug) }}" class="group block h-full p-8 bg-brand-surface hover:bg-brand-surface-highlight rounded-3xl border border-white/5 hover:border-brand-blue/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.5)]" aria-label="Browse {{ $category->name }} category">
                                    <div class="flex flex-col items-start gap-6">
                                        @if($category->icon)
                                            <div class="text-5xl group-hover:scale-110 transition-transform duration-300 text-brand-blue" aria-hidden="true">{{ $category->icon }}</div>
                                        @else
                                            <div class="w-16 h-16 bg-brand-blue/10 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300 border border-brand-blue/20" aria-hidden="true">
                                                <svg class="w-8 h-8 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="space-y-3">
                                            <h3 class="text-2xl font-bold text-white group-hover:text-brand-blue transition-colors">
                                                {{ $category->name }}
                                            </h3>
                                            @if($category->description)
                                                <p class="text-zinc-400 leading-relaxed">
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
            <section class="py-24 bg-brand-surface/30" aria-labelledby="top-products-heading">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 id="top-products-heading" class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6">
                            Top Selling Products
                        </h2>
                        <p class="text-lg text-zinc-400">
                            Most popular digital assets from our marketplace
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        @foreach($topProducts as $product)
                            <livewire:product.product-card :product="$product" :key="'top-product-'.$product->id" />
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- All Products Section -->
        <section id="products" class="py-24" aria-labelledby="latest-products-heading">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 id="latest-products-heading" class="text-3xl sm:text-4xl md:text-5xl font-bold mb-6">
                        Latest Products
                    </h2>
                    <p class="text-lg text-zinc-400">
                        Discover the newest additions to our marketplace
                    </p>
                </div>

                <livewire:product.product-list />
            </div>
        </section>
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
                        @if($categories->count() > 0)
                            @foreach($categories->take(3) as $category)
                                <li><a href="{{ route('category.show', $category->slug) }}" class="hover:text-brand-blue transition-colors">{{ $category->name }}</a></li>
                            @endforeach
                        @endif
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

    @livewireScripts
</body>
</html>
