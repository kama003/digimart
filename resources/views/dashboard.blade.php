<x-layouts.app :title="__('Dashboard')">
    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Welcome back, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                @if(auth()->user()->isAdmin())
                    You're logged in as an Administrator
                @elseif(auth()->user()->isSeller())
                    You're logged in as a Seller
                @else
                    You're logged in as a Customer
                @endif
            </p>
        </div>

        {{-- Admin Dashboard --}}
        @if(auth()->user()->isAdmin())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Total Users Card --}}
                <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Users</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\User::count() }}
                            </p>
                        </div>
                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Products Card --}}
                <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Products</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Product::count() }}
                            </p>
                        </div>
                        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                            <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pending Products Card --}}
                <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Approval</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\Product::where('is_approved', false)->count() }}
                            </p>
                        </div>
                        <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900">
                            <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Seller Requests Card --}}
                <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Seller Requests</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                                {{ \App\Models\SellerRoleRequest::where('status', 'pending')->count() }}
                            </p>
                        </div>
                        <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                            <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <flux:button :href="route('admin.users')" variant="outline" class="justify-start">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Manage Users
                    </flux:button>
                    <flux:button :href="route('admin.products')" variant="outline" class="justify-start">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Moderate Products
                    </flux:button>
                    <flux:button :href="route('admin.seller-requests')" variant="outline" class="justify-start">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Seller Requests
                    </flux:button>
                    <flux:button :href="route('home')" variant="outline" class="justify-start">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        View Marketplace
                    </flux:button>
                </div>
            </div>

        {{-- Seller Dashboard --}}
        @elseif(auth()->user()->isSeller())
            <div class="space-y-6">
                <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                    <p class="text-gray-600 dark:text-gray-400">
                        You have access to seller features. Visit your <a href="{{ route('seller.dashboard') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Seller Dashboard</a> to manage your products and view analytics.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <flux:button :href="route('seller.dashboard')" variant="outline" class="justify-start">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Seller Dashboard
                    </flux:button>
                    <flux:button :href="route('seller.products.create')" variant="outline" class="justify-start">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Upload Product
                    </flux:button>
                    <flux:button :href="route('seller.products.index')" variant="outline" class="justify-start">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        My Products
                    </flux:button>
                </div>
            </div>

        {{-- Customer Dashboard --}}
        @else
            <div class="space-y-6">
                <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Get Started</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Welcome to the Digital Marketplace! Browse our collection of premium digital assets or become a seller to start earning.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <flux:button :href="route('products.search')" variant="primary">
                            Browse Products
                        </flux:button>
                        <flux:button :href="route('seller-request')" variant="outline">
                            Become a Seller
                        </flux:button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
