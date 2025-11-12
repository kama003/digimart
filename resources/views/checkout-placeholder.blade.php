<x-layouts.app title="Checkout">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Checkout</h1>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Checkout functionality will be implemented in Task 5.
                </p>
                <a href="{{ route('cart.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    Back to Cart
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
