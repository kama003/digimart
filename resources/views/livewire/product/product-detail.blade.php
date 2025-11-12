<div class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-zinc-600 dark:text-zinc-400">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-green-600 dark:hover:text-green-500 transition-colors">
                        Home
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </li>
                @if($product->category)
                    <li>
                        <a href="{{ route('products.search', ['category_id' => $product->category->id]) }}" class="hover:text-green-600 dark:hover:text-green-500 transition-colors">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </li>
                @endif
                <li class="text-zinc-900 dark:text-zinc-100 truncate">
                    {{ $product->title }}
                </li>
            </ol>
        </nav>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg" role="alert" aria-live="polite">
                <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Product Detail Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column - Product Media -->
            <div class="space-y-4">
                <!-- Product Thumbnail -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm overflow-hidden border border-zinc-200 dark:border-zinc-700">
                    <div class="aspect-video bg-zinc-100 dark:bg-zinc-800">
                        @if($product->thumbnail_path)
                            <img 
                                src="{{ Storage::url($product->thumbnail_path) }}" 
                                alt="{{ $product->title }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center text-zinc-400 dark:text-zinc-600">
                                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="sr-only">No thumbnail available for {{ $product->title }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Audio/Video Preview Player -->
                @if(in_array($product->product_type, ['audio', 'video']) && $product->file_path)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm p-4 border border-zinc-200 dark:border-zinc-700">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Preview</h3>
                        
                        @if($product->product_type === 'audio')
                            <audio controls class="w-full">
                                <source src="{{ Storage::url($product->file_path) }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        @elseif($product->product_type === 'video')
                            <video controls class="w-full rounded">
                                <source src="{{ Storage::url($product->file_path) }}" type="video/mp4">
                                Your browser does not support the video element.
                            </video>
                        @endif
                    </div>
                @endif

                <!-- Product Details Card -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm p-6 border border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Product Details</h3>
                    
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-zinc-600 dark:text-zinc-400">Type</dt>
                            <dd class="text-sm font-medium text-zinc-900 dark:text-zinc-100 capitalize">
                                 {{ str_replace('_', ' ', $product->product_type->value) }}
                            </dd>
                        </div>
                        
                        @if($product->license_type)
                            <div class="flex justify-between">
                                <dt class="text-sm text-zinc-600 dark:text-zinc-400">License</dt>
                                <dd class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $product->license_type }}
                                </dd>
                            </div>
                        @endif
                        
                        @if($product->file_size)
                            <div class="flex justify-between">
                                <dt class="text-sm text-zinc-600 dark:text-zinc-400">File Size</dt>
                                <dd class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ number_format($product->file_size / 1024 / 1024, 2) }} MB
                                </dd>
                            </div>
                        @endif
                        
                        @if($product->downloads_count > 0)
                            <div class="flex justify-between">
                                <dt class="text-sm text-zinc-600 dark:text-zinc-400">Downloads</dt>
                                <dd class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ number_format($product->downloads_count) }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Right Column - Product Information -->
            <div class="space-y-6">
                <!-- Category Badge -->
                @if($product->category)
                    <div>
                        <a href="{{ route('products.search', ['category_id' => $product->category->id]) }}" 
                           class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors">
                            {{ $product->category->name }}
                        </a>
                    </div>
                @endif

                <!-- Product Title -->
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-2">
                        {{ $product->title }}
                    </h1>
                    
                    <!-- Short Description -->
                    @if($product->short_description)
                        <p class="text-lg text-zinc-600 dark:text-zinc-400">
                            {{ $product->short_description }}
                        </p>
                    @endif
                </div>

                <!-- Price and Add to Cart -->
                <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-4xl font-bold text-green-600 dark:text-green-500">
                            ${{ number_format($product->price, 2) }}
                        </span>
                    </div>
                    
                    <button 
                        wire:click="addToCart"
                        wire:loading.attr="disabled"
                        class="w-full bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-semibold py-4 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center text-base"
                    >
                        <span wire:loading.remove wire:target="addToCart">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Add to Cart
                        </span>
                        <span wire:loading wire:target="addToCart">
                            <svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Adding...
                        </span>
                    </button>
                </div>

                <!-- Seller Information -->
                @if($product->user)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm p-6 border border-zinc-200 dark:border-zinc-700">
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-3">Seller Information</h3>
                        
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 dark:text-green-500 font-semibold text-lg">
                                {{ strtoupper(substr($product->user->name, 0, 1)) }}
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-zinc-900 dark:text-zinc-100">
                                    {{ $product->user->name }}
                                </p>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 capitalize">
                                    {{ $product->user->role->value }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Full Description -->
                @if($product->description)
                    <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-sm p-6 border border-zinc-200 dark:border-zinc-700">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Description</h3>
                        <div class="prose prose-zinc dark:prose-invert max-w-none">
                            <p class="text-zinc-700 dark:text-zinc-300 whitespace-pre-line">{{ $product->description }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
