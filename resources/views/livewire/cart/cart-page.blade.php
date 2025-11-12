<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Shopping Cart</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Review your items before checkout
            </p>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg" role="alert" aria-live="polite">
                {{ session('success') }}
            </div>
        @endif

        @if($this->items->isEmpty())
            {{-- Empty Cart State --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center" role="status">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">Your cart is empty</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Start shopping to add items to your cart
                </p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Continue Shopping
                </a>
            </div>
        @else
            {{-- Cart Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Cart Items ({{ $this->items->count() }})
                        </h2>
                        <button type="button"
                                wire:click="clearCart"
                                wire:confirm="Are you sure you want to clear your entire cart?"
                                class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded px-2 py-1"
                                aria-label="Clear all items from cart">
                            Clear Cart
                        </button>
                    </div>

                    @foreach($this->items as $item)
                        <livewire:cart.cart-item :item="$item" :key="'cart-item-'.$item['product_id']" />
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <aside class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 sticky top-8" aria-labelledby="order-summary-heading">
                        <h2 id="order-summary-heading" class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                            Order Summary
                        </h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span>${{ number_format($this->total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Tax</span>
                                <span>$0.00</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                    <span>Total</span>
                                    <span>${{ number_format($this->total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('checkout') }}" 
                               class="block w-full text-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors mb-3 text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Proceed to Checkout
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors mb-3 text-base focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Login to Checkout
                            </a>
                        @endauth

                        <a href="{{ route('home') }}" 
                           class="block w-full text-center px-6 py-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-lg transition-colors text-base focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Continue Shopping
                        </a>

                        {{-- Security Badge --}}
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                </svg>
                                <span>Secure checkout powered by Stripe & Paytm</span>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        @endif
    </div>
</div>
