<article class="flex flex-col sm:flex-row gap-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="flex gap-4 flex-1">
        {{-- Product Image --}}
        <div class="flex-shrink-0">
            <img src="{{ $item['product']->thumbnail_path ? \Storage::url($item['product']->thumbnail_path) : 'https://via.placeholder.com/100' }}" 
                 alt="{{ $item['product']->title }}"
                 class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-md"
                 loading="lazy">
        </div>

        {{-- Product Details --}}
        <div class="flex-1 min-w-0">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                <a href="{{ route('product.show', $item['product']->slug) }}" 
                   class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    {{ $item['product']->title }}
                </a>
            </h3>
            
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ $item['product']->category->name ?? 'Uncategorized' }}
            </p>

            {{-- Price on mobile --}}
            <p class="sm:hidden text-lg font-bold text-gray-900 dark:text-white mt-2">
                ${{ number_format($item['subtotal'], 2) }}
            </p>
        </div>
    </div>

    {{-- Controls and Price --}}
    <div class="flex items-center justify-between sm:flex-col sm:items-end sm:justify-between gap-3">
        <div class="flex items-center gap-4 sm:order-2">
            {{-- Quantity Controls --}}
            <div class="flex items-center gap-2">
                <label for="quantity-{{ $item['product_id'] }}" class="text-sm text-gray-600 dark:text-gray-400">
                    Qty:
                </label>
                <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-md">
                    <button type="button"
                            wire:click="updateQuantity({{ $item['quantity'] - 1 }})"
                            class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-l-md"
                            aria-label="Decrease quantity of {{ $item['product']->title }}">
                        -
                    </button>
                    <input type="number" 
                           id="quantity-{{ $item['product_id'] }}"
                           wire:model.blur="item.quantity"
                           wire:change="updateQuantity($event.target.value)"
                           min="1"
                           class="w-16 text-center border-x border-gray-300 dark:border-gray-600 bg-transparent text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 py-3"
                           aria-label="Quantity for {{ $item['product']->title }}">
                    <button type="button"
                            wire:click="updateQuantity({{ $item['quantity'] + 1 }})"
                            class="px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-r-md"
                            aria-label="Increase quantity of {{ $item['product']->title }}">
                        +
                    </button>
                </div>
            </div>

            {{-- Remove Button --}}
            <button type="button"
                    wire:click="remove"
                    wire:confirm="Are you sure you want to remove this item from your cart?"
                    class="px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded"
                    aria-label="Remove {{ $item['product']->title }} from cart">
                Remove
            </button>
        </div>

        {{-- Price on desktop --}}
        <div class="hidden sm:block flex-shrink-0 text-right sm:order-1">
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                ${{ number_format($item['subtotal'], 2) }}
            </p>
            @if($item['quantity'] > 1)
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    ${{ number_format($item['product']->price, 2) }} each
                </p>
            @endif
        </div>
    </div>
</article>
