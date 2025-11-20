<article class="group bg-brand-surface rounded-2xl shadow-sm hover:shadow-[0_0_30px_rgba(0,122,255,0.15)] transition-all duration-300 overflow-hidden border border-white/5 hover:border-brand-blue/30 hover:-translate-y-1">
    <a href="{{ route('product.show', $product->slug) }}" class="block focus:outline-none focus:ring-2 focus:ring-brand-blue focus:ring-offset-2 rounded-2xl" aria-label="View {{ $product->title }} - ${{ number_format($product->price, 2) }}">
        <!-- Product Thumbnail -->
        <div class="aspect-video bg-zinc-900 overflow-hidden relative">
            @if($product->thumbnail_path)
                <img 
                    src="{{ Storage::url($product->thumbnail_path) }}" 
                    alt="{{ $product->title }}"
                    loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            @else
                <div class="w-full h-full flex items-center justify-center text-zinc-700 bg-zinc-900">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="sr-only">No thumbnail available</span>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="p-5">
            <!-- Category Badge -->
            @if($product->category)
                <div class="mb-3">
                    <a href="{{ route('category.show', $product->category->slug) }}" 
                       class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-white/5 text-zinc-400 hover:bg-brand-blue hover:text-white transition-colors border border-white/5"
                       onclick="event.stopPropagation()">
                        {{ $product->category->name }}
                    </a>
                </div>
            @endif

            <!-- Product Title -->
            <h3 class="text-lg font-bold text-white mb-2 line-clamp-2 group-hover:text-brand-blue transition-colors leading-tight">
                {{ $product->title }}
            </h3>

            <!-- Product Type -->
            @if($product->product_type)
                <p class="text-xs font-medium text-zinc-500 mb-4 uppercase tracking-wider">
                    {{ str_replace('_', ' ', $product->product_type->value) }}
                </p>
            @endif

            <!-- Price and Seller -->
            <div class="flex items-center justify-between pt-4 border-t border-white/5">
                <span class="text-xl font-bold text-white">
                    ${{ number_format($product->price, 2) }}
                </span>
                
                @if($product->user)
                    <span class="text-sm text-zinc-500 truncate ml-2 flex items-center gap-1">
                        <span class="w-1 h-1 rounded-full bg-zinc-600"></span>
                        {{ $product->user->name }}
                    </span>
                @endif
            </div>
        </div>
    </a>
</article>
