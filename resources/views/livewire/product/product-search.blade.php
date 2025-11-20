<div class="min-h-screen bg-brand-black pt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="mb-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">@if($currentCategory)
                    <span class="text-gradient-blue"> {{ $currentCategory->name }} </span>
                @else
                    Search <span class="text-gradient-blue">Products</span>
                @endif 
            </h1>
            <p class="text-lg text-zinc-400 max-w-2xl mx-auto">
                @if($currentCategory && $currentCategory->description)
                    {{ $currentCategory->description }}
                @else
                    Find the perfect digital asset for your project from our curated collection.
                @endif
            </p>
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
        <div class="bg-brand-surface rounded-2xl p-6 mb-10 border border-white/5 shadow-lg" role="search" aria-label="Product search filters">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Keyword Search -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="keyword" class="block text-sm font-medium text-zinc-300 mb-2">
                        Search
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="keyword"
                            wire:model.live.debounce.300ms="keyword"
                            placeholder="Search by title or description..."
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-zinc-300 mb-2">
                        Category
                    </label>
                    <select 
                        id="category"
                        wire:change="$dispatch('category-changed', { slug: $event.target.selectedOptions[0].dataset.slug })"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all appearance-none"
                    >
                        <option value="" data-slug="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" {{ $category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Type Filter -->
                <div>
                    <label for="product_type" class="block text-sm font-medium text-zinc-300 mb-2">
                        Type
                    </label>
                    <select 
                        id="product_type"
                        wire:model.live="product_type"
                        class="w-full bg-black/30 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all appearance-none"
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
                    <label class="block text-sm font-medium text-zinc-300 mb-2">
                        Price Range
                    </label>
                    <div class="flex gap-3">
                        <input 
                            type="number" 
                            wire:model.live.debounce.300ms="min_price"
                            placeholder="Min"
                            min="0"
                            step="0.01"
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-3 py-3 text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all text-sm"
                        >
                        <input 
                            type="number" 
                            wire:model.live.debounce.300ms="max_price"
                            placeholder="Max"
                            min="0"
                            step="0.01"
                            class="w-full bg-black/30 border border-white/10 rounded-xl px-3 py-3 text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-brand-blue focus:border-transparent transition-all text-sm"
                        >
                    </div>
                </div>
            </div>

            <!-- Active Filters and Reset Button -->
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-white/5 pt-4">
                <div class="flex flex-wrap gap-2">
                    @if($keyword)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-brand-blue/10 text-brand-blue border border-brand-blue/20">
                            Search: "{{ Str::limit($keyword, 20) }}"
                            <button wire:click="$set('keyword', '')" class="ml-2 hover:text-white transition-colors" aria-label="Clear search">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($category_id)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-brand-blue/10 text-brand-blue border border-brand-blue/20">
                            Category: {{ $categories->firstWhere('id', $category_id)?->name }}
                            <button wire:click="$set('category_id', '')" class="ml-2 hover:text-white transition-colors" aria-label="Clear category filter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($product_type)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-brand-blue/10 text-brand-blue border border-brand-blue/20">
                            Type: {{ ucfirst($product_type) }}
                            <button wire:click="$set('product_type', '')" class="ml-2 hover:text-white transition-colors" aria-label="Clear type filter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                    @if($min_price || $max_price)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm bg-brand-blue/10 text-brand-blue border border-brand-blue/20">
                            Price: ${{ $min_price ?: '0' }} - ${{ $max_price ?: '∞' }}
                            <button wire:click="$set('min_price', ''); $set('max_price', '')" class="ml-2 hover:text-white transition-colors" aria-label="Clear price filter">
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
                        class="text-sm text-zinc-400 hover:text-white transition-colors font-medium self-start sm:self-auto flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Clear All Filters
                    </button>
                @endif
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading class="mb-8 w-full">
            <div class="bg-brand-blue/10 border border-brand-blue/20 rounded-xl p-4 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 text-brand-blue mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-brand-blue font-medium">Searching products...</span>
            </div>
        </div>

        <!-- Results Count -->
        <div class="mb-6">
            <p class="text-sm text-zinc-400">
                Found <span class="font-semibold text-white">{{ $products->total() }}</span> products
            </p>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
                @foreach($products as $product)
                    <livewire:product.product-card :product="$product" :key="'product-'.$product->id" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="bg-brand-surface rounded-2xl border border-white/5 p-16 text-center">
                <div class="w-20 h-20 bg-zinc-900 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-10 w-10 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">No products found</h3>
                <p class="text-zinc-400 mb-8 max-w-md mx-auto">We couldn't find any products matching your search criteria. Try adjusting your filters or search terms.</p>
                @if($keyword || $category_id || $product_type || $min_price || $max_price)
                    <button 
                        wire:click="resetFilters"
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-brand-blue hover:bg-brand-blue-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition-all"
                    >
                        Clear All Filters
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
