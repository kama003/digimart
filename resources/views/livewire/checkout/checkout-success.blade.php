<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold mb-4 dark:text-white">Payment Successful!</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            Thank you for your purchase. Your order is being processed.
        </p>

        <!-- Transaction Details -->
        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 mb-8 text-left">
            <h2 class="text-lg font-semibold mb-4 dark:text-white">Order Details</h2>
            
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Transaction ID:</span>
                    <span class="font-mono dark:text-white">{{ $transaction->id }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Payment Method:</span>
                    <span class="dark:text-white">{{ ucfirst($transaction->payment_gateway) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                    <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-200 rounded text-xs">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                <div class="flex justify-between text-sm font-semibold pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="dark:text-white">Total Amount:</span>
                    <span class="dark:text-white">${{ number_format($transaction->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-8 text-left">
            <h3 class="font-semibold mb-2 text-blue-900 dark:text-blue-200">What's Next?</h3>
            <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-2">
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>You will receive a confirmation email with download links shortly</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Your purchased items will be available in your purchase history</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Download links are valid for {{ config('download.expiry_hours', 24) }} hours</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Go to Dashboard
            </a>
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-semibold rounded-lg transition-colors">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
