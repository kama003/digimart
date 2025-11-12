<div class="space-y-6">
    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Seller Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Overview of your sales performance and earnings</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" role="region" aria-label="Sales statistics">
        {{-- Total Revenue Card --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white" aria-label="Total revenue: ${{ number_format($totalRevenue, 2) }}">
                        ${{ number_format($totalRevenue, 2) }}
                    </p>
                </div>
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900" aria-hidden="true">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Downloads Card --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Downloads</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white" aria-label="Total downloads: {{ number_format($totalDownloads) }}">
                        {{ number_format($totalDownloads) }}
                    </p>
                </div>
                <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900" aria-hidden="true">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending Balance Card --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Balance</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format($pendingBalance, 2) }}
                    </p>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                    <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('seller.withdrawals.create') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                    Request Withdrawal
                </a>
            </div>
        </div>
    </div>

    {{-- Top Products Section --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Top Products by Revenue --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Products by Revenue</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Your best-selling products</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topProductsByRevenue as $item)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-4">
                            @if($item->product)
                                <img src="{{ Storage::url($item->product->thumbnail_path) }}" 
                                     alt="{{ $item->product->title }}" 
                                     class="h-12 w-12 rounded object-cover">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ Str::limit($item->product->title, 30) }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->sales_count }} {{ Str::plural('sale', $item->sales_count) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($item->total_revenue, 2) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-600 dark:text-gray-400">
                        No sales yet. Start uploading products!
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Products by Downloads --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Products by Downloads</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Most downloaded products</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topProductsByDownloads as $item)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-4">
                            @if($item->product)
                                <img src="{{ Storage::url($item->product->thumbnail_path) }}" 
                                     alt="{{ $item->product->title }}" 
                                     class="h-12 w-12 rounded object-cover">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ Str::limit($item->product->title, 30) }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->product->category->name ?? 'Uncategorized' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ number_format($item->download_count) }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">downloads</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-600 dark:text-gray-400">
                        No downloads yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Withdrawals --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Withdrawals</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Your completed withdrawal requests</p>
                </div>
                <a href="{{ route('seller.withdrawals') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    View All
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Method
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @forelse($recentWithdrawals as $withdrawal)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $withdrawal->processed_at?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                ${{ number_format($withdrawal->amount, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ ucfirst(str_replace('_', ' ', $withdrawal->method)) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold
                                    {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $withdrawal->status === 'approved' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-600 dark:text-gray-400">
                                No withdrawals yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
