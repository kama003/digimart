<div class="w-full">
    @if($products->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <livewire:product.product-card :product="$product" :key="'product-'.$product->id" />
            @endforeach
        </div>

        <!-- Load More Button -->
        @if($products->hasMorePages())
            <div class="mt-8 flex justify-center">
                <button 
                    wire:click="loadMore" 
                    wire:loading.attr="disabled"
                    class="px-8 py-4 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-lg font-medium hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-base"
                >
                    <span wire:loading.remove wire:target="loadMore">Load More</span>
                    <span wire:loading wire:target="loadMore" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Loading...
                    </span>
                </button>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">No products found</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Get started by browsing our categories or check back later.</p>
        </div>
    @endif
</div>
