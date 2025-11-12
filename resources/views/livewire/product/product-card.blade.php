<article class="group bg-white dark:bg-zinc-900 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-zinc-200 dark:border-zinc-700">
    <a href="{{ route('product.show', $product->slug) }}" class="block focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg" aria-label="View {{ $product->title }} - ${{ number_format($product->price, 2) }}">
        <!-- Product Thumbnail -->
        <div class="aspect-video bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
            @if($product->thumbnail_path)
                <img 
                    src="{{ Storage::url($product->thumbnail_path) }}" 
                    alt="{{ $product->title }}"
                    loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                >
            @else
                <div class="w-full h-full flex items-center justify-center text-zinc-400 dark:text-zinc-600">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="sr-only">No thumbnail available</span>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="p-4">
            <!-- Category Badge -->
            @if($product->category)
                <div class="mb-2">
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300">
                        {{ $product->category->name }}
                    </span>
                </div>
            @endif

            <!-- Product Title -->
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-2 line-clamp-2 group-hover:text-green-600 dark:group-hover:text-green-500 transition-colors">
                {{ $product->title }}
            </h3>

            <!-- Product Type -->
            @if($product->product_type)
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-3 capitalize">
                    {{ str_replace('_', ' ', $product->product_type->value) }}
                </p>
            @endif

            <!-- Price and Seller -->
            <div class="flex items-center justify-between">
                <span class="text-xl font-bold text-green-600 dark:text-green-500">
                    ${{ number_format($product->price, 2) }}
                </span>
                
                @if($product->user)
                    <span class="text-sm text-zinc-500 dark:text-zinc-400 truncate ml-2">
                        by {{ $product->user->name }}
                    </span>
                @endif
            </div>

            <!-- Downloads Count -->
            @if($product->downloads_count > 0)
                <div class="mt-3 pt-3 border-t border-zinc-200 dark:border-zinc-700">
                    <span class="text-xs text-zinc-500 dark:text-zinc-400">
                        {{ number_format($product->downloads_count) }} {{ Str::plural('download', $product->downloads_count) }}
                    </span>
                </div>
            @endif
        </div>
    </a>
</article>
