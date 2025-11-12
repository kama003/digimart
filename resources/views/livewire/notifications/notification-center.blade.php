<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" wire:poll.30s>
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
            <button 
                wire:click="markAllAsRead"
                class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
            >
                Mark all as read
            </button>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        @forelse($notifications as $notification)
            <div 
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $notification->read_at ? 'opacity-60' : '' }}"
            >
                <div class="flex items-start">
                    <!-- Unread indicator -->
                    <div class="flex-shrink-0 mr-4">
                        @if(!$notification->read_at)
                            <span class="inline-block w-3 h-3 bg-blue-600 rounded-full"></span>
                        @else
                            <span class="inline-block w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></span>
                        @endif
                    </div>

                    <!-- Notification Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $notification->data['message'] ?? 'New notification' }}
                                </p>
                                
                                <!-- Additional details based on notification type -->
                                @if(isset($notification->data['product_title']))
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Product: {{ $notification->data['product_title'] }}
                                    </p>
                                @endif

                                @if(isset($notification->data['amount']))
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Amount: ${{ number_format($notification->data['amount'], 2) }}
                                    </p>
                                @endif

                                @if(isset($notification->data['rejection_reason']))
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                        Reason: {{ $notification->data['rejection_reason'] }}
                                    </p>
                                @endif

                                @if(isset($notification->data['reason']))
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                        Reason: {{ $notification->data['reason'] }}
                                    </p>
                                @endif

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Mark as read button -->
                            @if(!$notification->read_at)
                                <button 
                                    wire:click="markAsRead('{{ $notification->id }}')"
                                    class="ml-4 text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                                >
                                    Mark as read
                                </button>
                            @endif
                        </div>

                        <!-- Action buttons based on notification type -->
                        <div class="mt-3 flex gap-2">
                            @if(isset($notification->data['product_slug']))
                                <a 
                                    href="{{ route('product.show', $notification->data['product_slug']) }}"
                                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                                    wire:navigate
                                >
                                    View Product
                                </a>
                            @endif

                            @if(isset($notification->data['transaction_id']))
                                <a 
                                    href="{{ route('customer.purchases') }}"
                                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                                    wire:navigate
                                >
                                    View Purchase
                                </a>
                            @endif

                            @if(isset($notification->data['withdrawal_id']))
                                <a 
                                    href="{{ route('seller.withdrawals') }}"
                                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                                    wire:navigate
                                >
                                    View Withdrawal
                                </a>
                            @endif

                            @if(isset($notification->data['seller_role_request_id']))
                                <a 
                                    href="{{ route('admin.seller-requests') }}"
                                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                                    wire:navigate
                                >
                                    Review Request
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No notifications</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
