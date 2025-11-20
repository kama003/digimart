<div class="min-h-screen bg-brand-black pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb -->
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-400">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-white transition-colors">
                        Home
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-zinc-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                @if($product->category)
                    <li>
                        <a href="{{ route('category.show', $product->category->slug) }}" class="hover:text-white transition-colors">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4 text-zinc-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                @endif
                <li class="text-white truncate font-medium">
                    {{ $product->title }}
                </li>
            </ol>
        </nav>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 flex items-center" role="alert" aria-live="polite">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Product Detail Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Left Column - Product Media -->
            <div class="space-y-6">
                <!-- Product Thumbnail -->
                <div class="bg-brand-surface rounded-2xl shadow-lg overflow-hidden border border-white/5 relative group">
                    <div class="aspect-video bg-zinc-900 relative">
                        @if($product->thumbnail_path)
                            <img 
                                src="{{ Storage::url($product->thumbnail_path) }}" 
                                alt="{{ $product->title }}"
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-600 bg-zinc-900">
                                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="sr-only">No thumbnail available for {{ $product->title }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Preview Section -->
                @if($product->preview_path)
                    <div class="bg-brand-surface rounded-2xl shadow-lg p-6 border border-white/5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Preview</h3>
                            <span class="text-xs text-brand-blue bg-brand-blue/10 px-2.5 py-1 rounded-full border border-brand-blue/20 font-medium">
                                Limited Preview
                            </span>
                        </div>
                        
                        <div class="rounded-xl overflow-hidden bg-black/30 border border-white/5">
                            @if($product->product_type->value === 'audio')
                                <audio controls controlsList="nodownload" class="w-full" oncontextmenu="return false;">
                                    <source src="{{ Storage::url($product->preview_path) }}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            @elseif($product->product_type->value === 'video')
                                <video controls controlsList="nodownload" class="w-full" oncontextmenu="return false;">
                                    <source src="{{ Storage::url($product->preview_path) }}" type="video/mp4">
                                    Your browser does not support the video element.
                                </video>
                            @elseif(in_array($product->product_type->value, ['graphic', 'template', '3d']))
                                <div class="relative">
                                    <img 
                                        src="{{ Storage::url($product->preview_path) }}" 
                                        alt="Preview of {{ $product->title }}"
                                        class="w-full"
                                        oncontextmenu="return false;"
                                        style="user-select: none; -webkit-user-select: none; -moz-user-select: none;"
                                    >
                                    <div class="absolute inset-0 pointer-events-none"></div>
                                </div>
                            @endif
                        </div>
                        
                        <p class="mt-4 text-xs text-zinc-500 text-center">
                            This is a limited preview. Purchase to access the full quality file.
                        </p>
                    </div>
                @else
                    <div class="bg-brand-surface rounded-2xl p-8 border border-white/5 text-center">
                        <svg class="w-12 h-12 mx-auto text-zinc-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-zinc-500">
                            No preview available for this product
                        </p>
                    </div>
                @endif

                <!-- Product Details Card -->
                <div class="bg-brand-surface rounded-2xl shadow-lg p-6 border border-white/5">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-6">Product Details</h3>
                    
                    <dl class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-white/5 last:border-0 last:pb-0">
                            <dt class="text-sm text-zinc-400">Type</dt>
                            <dd class="text-sm font-medium text-white capitalize bg-white/5 px-3 py-1 rounded-full">
                                 {{ str_replace('_', ' ', $product->product_type->value) }}
                            </dd>
                        </div>
                        
                        @if($product->license_type)
                            <div class="flex justify-between items-center pb-4 border-b border-white/5 last:border-0 last:pb-0">
                                <dt class="text-sm text-zinc-400">License</dt>
                                <dd class="text-sm font-medium text-white">
                                    {{ $product->license_type }}
                                </dd>
                            </div>
                        @endif
                        
                        @if($product->file_size)
                            <div class="flex justify-between items-center pb-4 border-b border-white/5 last:border-0 last:pb-0">
                                <dt class="text-sm text-zinc-400">File Size</dt>
                                <dd class="text-sm font-medium text-white">
                                    {{ number_format($product->file_size / 1024 / 1024, 2) }} MB
                                </dd>
                            </div>
                        @endif
                        
                        @if($product->downloads_count > 0)
                            <div class="flex justify-between items-center pb-4 border-b border-white/5 last:border-0 last:pb-0">
                                <dt class="text-sm text-zinc-400">Downloads</dt>
                                <dd class="text-sm font-medium text-white flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    {{ number_format($product->downloads_count) }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Right Column - Product Information -->
            <div class="space-y-8">
                <!-- Category Badge -->
                @if($product->category)
                    <div>
                        <a href="{{ route('category.show', $product->category->slug) }}" 
                           class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-brand-blue/10 text-brand-blue border border-brand-blue/20 hover:bg-brand-blue/20 transition-colors">
                            {{ $product->category->name }}
                        </a>
                    </div>
                @endif

                <!-- Product Title -->
                <div>
                    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4 leading-tight">
                        {{ $product->title }}
                    </h1>
                    
                    <!-- Short Description -->
                    @if($product->short_description)
                        <p class="text-xl text-zinc-400 leading-relaxed">
                            {{ $product->short_description }}
                        </p>
                    @endif
                </div>

                <!-- Price and Add to Cart -->
                <div class="bg-brand-surface rounded-2xl shadow-lg p-8 border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-brand-blue/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
                    
                    <div class="flex items-center justify-between mb-8 relative">
                        <div>
                            <p class="text-sm text-zinc-400 mb-1">Total Price</p>
                            <span class="text-5xl font-bold text-white tracking-tight">
                                ${{ number_format($product->price, 2) }}
                            </span>
                        </div>
                    </div>
                    
                    <button 
                        wire:click="addToCart"
                        wire:loading.attr="disabled"
                        class="w-full bg-brand-blue hover:bg-brand-blue-hover text-white font-bold py-4 px-6 rounded-xl transition-all shadow-lg shadow-brand-blue/25 hover:shadow-brand-blue/40 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-lg relative z-10"
                    >
                        <span wire:loading.remove wire:target="addToCart" class="flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Add to Cart
                        </span>
                        <span wire:loading wire:target="addToCart" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Adding...
                        </span>
                    </button>
                </div>

                <!-- Seller Information -->
                @if($product->user)
                    <div class="bg-brand-surface rounded-2xl shadow-lg p-6 border border-white/5">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Seller Information</h3>
                        
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-zinc-800 rounded-full flex items-center justify-center text-zinc-400 font-bold text-xl border border-white/5">
                                {{ strtoupper(substr($product->user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <p class="font-bold text-white text-lg">
                                    {{ $product->user->name }}
                                </p>
                                <p class="text-sm text-zinc-400 capitalize flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $product->user->role->value }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Full Description -->
                @if($product->description)
                    <div class="bg-brand-surface rounded-2xl shadow-lg p-8 border border-white/5">
                        <h3 class="text-xl font-bold text-white mb-6">Description</h3>
                        <div class="prose prose-lg prose-invert max-w-none prose-p:text-zinc-300 prose-headings:text-white prose-strong:text-white prose-ul:text-zinc-300">
                            <p class="whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                    </div>
                @endif

                <!-- Reviews Section -->
                <div class="space-y-8 pt-8 border-t border-white/5">
                    <livewire:product.product-reviews :product="$product" />
                    <livewire:product.review-form :product="$product" />
                </div>
            </div>
        </div>
    </div>
</div>
