<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <h1 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8 dark:text-white">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2">
            <section class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6" aria-labelledby="order-summary-heading">
                <h2 id="order-summary-heading" class="text-xl font-semibold mb-4 dark:text-white">Order Summary</h2>
                
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex items-center gap-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            @if($item['product']['thumbnail_path'])
                                <img src="{{ Storage::url($item['product']['thumbnail_path']) }}" 
                                     alt="{{ $item['product']['title'] }}"
                                     class="w-20 h-20 object-cover rounded">
                            @else
                                <div class="w-20 h-20 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-semibold dark:text-white">{{ $item['product']['title'] }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ ucfirst($item['product']['product_type']->value ?? $item['product']['product_type']) }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Quantity: {{ $item['quantity'] }}
                                </p>
                            </div>
                            
                            <div class="text-right">
                                <p class="font-semibold dark:text-white">${{ number_format($item['subtotal'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Gateway Selection -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">Payment Method</h2>
                
                @if($errorMessage)
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg" role="alert" aria-live="assertive">
                        <p class="text-red-800 dark:text-red-200">{{ $errorMessage }}</p>
                    </div>
                @endif

                @if(!$clientSecret)
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors
                                    {{ $payment_gateway === 'stripe' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                            <input type="radio" 
                                   wire:model.live="payment_gateway" 
                                   value="stripe" 
                                   class="mr-3">
                            <div class="flex-1">
                                <span class="font-semibold dark:text-white">Credit/Debit Card (Stripe)</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pay securely with your credit or debit card</p>
                            </div>
                            <svg class="w-12 h-8" viewBox="0 0 60 25" fill="none">
                                <rect width="60" height="25" rx="4" fill="#635BFF"/>
                                <path d="M13.3 11.5c0-.9.7-1.4 1.9-1.4 1.7 0 3.9.5 5.6 1.4V8.3c-1.8-.7-3.6-1-5.4-1-4.4 0-7.4 2.3-7.4 6.1 0 6 8.2 5 8.2 7.6 0 1-.9 1.5-2.1 1.5-1.8 0-4.2-.8-6-1.8v3.3c2 .9 4 1.3 6 1.3 4.5 0 7.6-2.2 7.6-6.1 0-6.4-8.4-5.3-8.4-7.7zm13.5-4.1v-.9c0-1.8 1-2.6 2.4-2.6.9 0 1.6.2 2.4.5V1.5c-.8-.3-1.6-.5-2.6-.5-3.3 0-5.5 2.2-5.5 5.8v1.6h-2.3v3.1h2.3v12.4h3.3V11.5h3.8V8.4h-3.8zm14.8-.1c-1.3 0-2.3.6-2.8 1.5l-.2-1.2h-2.9v16.3h3.3v-5.5c.5.7 1.4 1.2 2.7 1.2 2.7 0 5.1-2.2 5.1-6.2 0-3.9-2.4-6.1-5.2-6.1zm-.8 9.2c-1.5 0-2.5-1.1-2.5-3.1s1-3.1 2.5-3.1c1.5 0 2.5 1.1 2.5 3.1s-1 3.1-2.5 3.1z" fill="white"/>
                            </svg>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-colors
                                    {{ $payment_gateway === 'paytm' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                            <input type="radio" 
                                   wire:model.live="payment_gateway" 
                                   value="paytm" 
                                   class="mr-3">
                            <div class="flex-1">
                                <span class="font-semibold dark:text-white">Paytm</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pay with Paytm wallet or UPI</p>
                            </div>
                            <svg class="w-12 h-8" viewBox="0 0 60 25" fill="none">
                                <rect width="60" height="25" rx="4" fill="#00BAF2"/>
                                <text x="30" y="17" font-family="Arial" font-size="10" font-weight="bold" fill="white" text-anchor="middle">Paytm</text>
                            </svg>
                        </label>
                    </div>

                    @error('payment_gateway')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                @else
                    <!-- Payment Form Container -->
                    <div id="payment-form-container">
                        @if($payment_gateway === 'stripe')
                            <div id="stripe-payment-element" class="mb-4"></div>
                            <div id="stripe-error-message" class="text-red-600 dark:text-red-400 text-sm mb-4"></div>
                        @elseif($payment_gateway === 'paytm')
                            <div id="paytm-payment-container" class="mb-4">
                                <p class="text-gray-600 dark:text-gray-400 mb-4">You will be redirected to Paytm to complete your payment.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Price Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-semibold mb-4 dark:text-white">Price Details</h2>
                
                <div class="space-y-3 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>Platform Fee ({{ config('payment.commission_percent') }}%)</span>
                        <span>${{ number_format($commission, 2) }}</span>
                    </div>
                </div>
                
                <div class="flex justify-between text-xl font-bold mb-6 dark:text-white">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                @if(!$clientSecret)
                    <button wire:click="processCheckout" 
                            wire:loading.attr="disabled"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-base">
                        <span wire:loading.remove wire:target="processCheckout">Continue to Payment</span>
                        <span wire:loading wire:target="processCheckout">Processing...</span>
                    </button>
                @else
                    <button id="submit-payment-button"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-base">
                        <span id="button-text">Pay ${{ number_format($total, 2) }}</span>
                        <span id="button-spinner" class="hidden">Processing...</span>
                    </button>
                @endif

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center">
                    Your payment information is secure and encrypted
                </p>
            </div>
        </div>
    </div>
</div>

@if($clientSecret && $payment_gateway === 'stripe')
    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let stripe = null;
            let elements = null;
            let paymentElement = null;

            Livewire.on('payment-intent-created', (event) => {
                const { clientSecret, gateway } = event[0];
                
                if (gateway === 'stripe') {
                    initializeStripe(clientSecret);
                } else if (gateway === 'paytm') {
                    initializePaytm(clientSecret);
                }
            });

            function initializeStripe(clientSecret) {
                const stripeKey = '{{ config('payment.stripe.key') }}';
                
                if (!stripeKey) {
                    console.error('Stripe key not configured');
                    return;
                }

                stripe = Stripe(stripeKey);
                elements = stripe.elements({ clientSecret });
                
                paymentElement = elements.create('payment');
                paymentElement.mount('#stripe-payment-element');

                // Handle form submission
                const submitButton = document.getElementById('submit-payment-button');
                const buttonText = document.getElementById('button-text');
                const buttonSpinner = document.getElementById('button-spinner');

                submitButton.addEventListener('click', async (e) => {
                    e.preventDefault();
                    
                    submitButton.disabled = true;
                    buttonText.classList.add('hidden');
                    buttonSpinner.classList.remove('hidden');

                    const { error } = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: '{{ route('checkout.success', ['transaction' => '__TRANSACTION_ID__']) }}',
                        },
                        redirect: 'if_required',
                    });

                    if (error) {
                        const errorElement = document.getElementById('stripe-error-message');
                        errorElement.textContent = error.message;
                        
                        submitButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        buttonSpinner.classList.add('hidden');

                        @this.call('handlePaymentFailure', error.message);
                    } else {
                        @this.call('handlePaymentSuccess');
                    }
                });
            }

            function initializePaytm(txnToken) {
                // Paytm integration would go here
                // This typically involves redirecting to Paytm's payment page
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ config('payment.paytm.environment') }}' === 'production' 
                    ? 'https://securegw.paytm.in/theia/api/v1/showPaymentPage'
                    : 'https://securegw-stage.paytm.in/theia/api/v1/showPaymentPage';
                
                const mid = document.createElement('input');
                mid.type = 'hidden';
                mid.name = 'mid';
                mid.value = '{{ config('payment.paytm.merchant_id') }}';
                form.appendChild(mid);

                const orderId = document.createElement('input');
                orderId.type = 'hidden';
                orderId.name = 'orderId';
                orderId.value = @this.paymentId;
                form.appendChild(orderId);

                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = 'txnToken';
                token.value = txnToken;
                form.appendChild(token);

                document.body.appendChild(form);
                
                const submitButton = document.getElementById('submit-payment-button');
                submitButton.addEventListener('click', () => {
                    form.submit();
                });
            }
        });
    </script>
    @endpush
@endif
