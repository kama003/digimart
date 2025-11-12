<div 
    x-data="{ open: @entangle('isOpen') }"
    @click.away="open = false"
    class="relative"
>
    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($this->recentNotifications as $notification)
                <div 
                    wire:click="markAsRead('{{ $notification->id }}')"
                    class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 {{ $notification->read_at ? 'opacity-60' : '' }}"
                >
                    <div class="flex items-start">
                        <!-- Unread indicator -->
                        @if(!$notification->read_at)
                            <span class="flex-shrink-0 w-2 h-2 mt-2 mr-3 bg-blue-600 rounded-full"></span>
                        @else
                            <span class="flex-shrink-0 w-2 h-2 mt-2 mr-3"></span>
                        @endif

                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $notification->data['message'] ?? 'New notification' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($this->recentNotifications->count() > 0)
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <a 
                    href="{{ route('notifications') }}" 
                    class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500"
                    wire:navigate
                >
                    View all notifications
                </a>
            </div>
        @endif
    </div>
</div>
