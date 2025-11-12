<div class="relative">
    <a href="{{ route('cart.index') }}" 
       class="flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-lg p-1"
       aria-label="Shopping cart with {{ $this->itemCount }} {{ Str::plural('item', $this->itemCount) }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
        
        @if($this->itemCount > 0)
            <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
                  aria-live="polite"
                  aria-atomic="true">
                <span class="sr-only">{{ $this->itemCount }} {{ Str::plural('item', $this->itemCount) }} in cart</span>
                <span aria-hidden="true">{{ $this->itemCount }}</span>
            </span>
        @endif
    </a>
</div>
