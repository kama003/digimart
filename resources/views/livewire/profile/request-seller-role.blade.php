<div>
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Become a Seller</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Request to upgrade your account to a seller account to start selling digital products.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
        @endif

        @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        You already have seller privileges.
                    </p>
                </div>
            </div>
        @elseif($hasPendingRequest)
            <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            Your request is pending review
                        </p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">
                            Submitted on {{ $latestRequest->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @elseif($latestRequest && $latestRequest->status === 'rejected')
            <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">
                    Your previous request was rejected
                </p>
                @if($latestRequest->admin_notes)
                    <p class="text-sm text-red-700 dark:text-red-300">
                        Reason: {{ $latestRequest->admin_notes }}
                    </p>
                @endif
                <p class="text-xs text-red-600 dark:text-red-400 mt-2">
                    You can submit a new request below.
                </p>
            </div>
            <flux:button wire:click="submitRequest" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submitRequest">Submit New Request</span>
                <span wire:loading wire:target="submitRequest">Submitting...</span>
            </flux:button>
        @else
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Benefits of becoming a seller:</h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Upload and sell digital products
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Access to seller dashboard and analytics
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Manage your products and earnings
                        </li>
                        <li class="flex items-start">
                            <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Request withdrawals of your earnings
                        </li>
                    </ul>
                </div>

                <flux:button wire:click="submitRequest" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submitRequest">Request Seller Role</span>
                    <span wire:loading wire:target="submitRequest">Submitting...</span>
                </flux:button>
            </div>
        @endif
    </div>
</div>
