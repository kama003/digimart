<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Search Products</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Find the perfect digital asset for your project</p>
        </div>

        <!-- Screen reader announcement for search results -->
        <div class="sr-only" role="status" aria-live="polite" aria-atomic="true">
            @if($products->total() > 0)
                Found {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
            @else
                No products found
            @endif
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 sm:p-6 mb-6 sm:mb-8" role="search" aria-label="Product search filters">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Keyword Search -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="keyword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search
                    </label>
                    <input 
                        type="text" 
                        id="keyword"
                        wire:model.live.debounce.300ms="keyword"
                        placeholder="Search by title or description..."
                        class="p-2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category
                    </label>
                    <select 
                        id="category"
                        wire:model.live="category_id"
                        class="p-2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Type Filter -->
                <div>
                    <label for="product_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Type
                    </label>
                    <select 
                        id="product_type"
                        wire:model.live="product_type"
                        class="p-2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All Types</option>
                        <option value="audio">Audio</option>
                        <option value="video">Video</option>
                        <option value="3d">3D Model</option>
                        <option value="template">Template</option>
                        <option value="graphic">Graphic</option>
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price Range
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="number" 
                            wire:model.live.debounce.300ms="min_price"
                            placeholder="Min"
                            min="0"
                            step="0.01"
                            class="p-2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                        <input 
                            type="number" 
                            wire:model.live.debounce.300ms="max_price"
                            placeholder="Max"
                            min="0"
                            step="0.01"
                            class="p-2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                    </div>
                </div>
            </div>

            <!-- Active Filters and Reset Button -->
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex flex-wrap gap-2">
                    @if($keyword)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            Search: "{{ Str::limit($keyword, 20) }}"
                            <button wire:click="$set('keyword', '')" class="ml-2 hover:text-indigo-600" aria-label="Clear search">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($category_id)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            Category: {{ $categories->firstWhere('id', $category_id)?->name }}
                            <button wire:click="$set('category_id', '')" class="ml-2 hover:text-indigo-600" aria-label="Clear category filter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($product_type)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            Type: {{ ucfirst($product_type) }}
                            <button wire:click="$set('product_type', '')" class="ml-2 hover:text-indigo-600" aria-label="Clear type filter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($min_price || $max_price)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                            Price: ${{ $min_price ?: '0' }} - ${{ $max_price ?: '∞' }}
                            <button wire:click="$set('min_price', ''); $set('max_price', '')" class="ml-2 hover:text-indigo-600" aria-label="Clear price filter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                </div>

                @if($keyword || $category_id || $product_type || $min_price || $max_price)
                    <button 
                        wire:click="resetFilters"
                        class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium self-start sm:self-auto"
                    >
                        Clear All Filters
                    </button>
                @endif
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading class="mb-4">
            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="animate-spin h-5 w-5 text-indigo-600 dark:text-indigo-400 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-indigo-600 dark:text-indigo-400 font-medium">Searching products...</span>
                </div>
            </div>
        </div>

        <!-- Results Count -->
        <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Found <span class="font-semibold text-gray-900 dark:text-white">{{ $products->total() }}</span> products
            </p>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($products as $product)
                    <livewire:product.product-card :product="$product" :key="'product-'.$product->id" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No products found</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Try adjusting your search filters to find what you're looking for.</p>
                @if($keyword || $category_id || $product_type || $min_price || $max_price)
                    <button 
                        wire:click="resetFilters"
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Clear All Filters
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
