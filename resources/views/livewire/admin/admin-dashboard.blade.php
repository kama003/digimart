<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Platform-wide analytics and performance metrics</p>
        </div>
        
        {{-- Period Selector --}}
        <div class="flex space-x-2">
            <button 
                wire:click="setPeriod('daily')" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $period === 'daily' ? 'text-white bg-blue-600 hover:bg-blue-700' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                Daily
            </button>
            <button 
                wire:click="setPeriod('weekly')" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $period === 'weekly' ? 'text-white bg-blue-600 hover:bg-blue-700' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                Weekly
            </button>
            <button 
                wire:click="setPeriod('monthly')" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $period === 'monthly' ? 'text-white bg-blue-600 hover:bg-blue-700' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                Monthly
            </button>
            <button 
                wire:click="setPeriod('all')" 
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $period === 'all' ? 'text-white bg-blue-600 hover:bg-blue-700' : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700' }}">
                All Time
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4" role="region" aria-label="Platform statistics">
        {{-- Total Platform Revenue Card --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Platform Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white" aria-label="Platform revenue: ${{ number_format($totalPlatformRevenue, 2) }}">
                        ${{ number_format($totalPlatformRevenue, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">Commission earned</p>
                </div>
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900" aria-hidden="true">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Transactions Card --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transactions</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white" aria-label="Total transactions: {{ number_format($totalTransactions) }}">
                        {{ number_format($totalTransactions) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">Completed orders</p>
                </div>
                <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900" aria-hidden="true">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
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
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">All products</p>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900" aria-hidden="true">
                    <svg class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Active Users Card --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Users</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ number_format(array_sum($usersByRole)) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-500">
                        {{ $usersByRole['seller'] ?? 0 }} sellers, {{ $usersByRole['customer'] ?? 0 }} customers
                    </p>
                </div>
                <div class="rounded-full bg-orange-100 p-3 dark:bg-orange-900" aria-hidden="true">
                    <svg class="h-8 w-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Trend Charts --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Platform Revenue Chart --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Platform Revenue Trend</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Commission earned over time ({{ ucfirst($period) }})
                </p>
            </div>
            <div class="p-6">
                <div wire:ignore>
                    <canvas id="revenueChart" class="h-64"></canvas>
                </div>
            </div>
        </div>

        {{-- Transaction Volume Chart --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Transaction Volume</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Number of completed transactions ({{ ucfirst($period) }})
                </p>
            </div>
            <div class="p-6">
                <div wire:ignore>
                    <canvas id="transactionChart" class="h-64"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Products and Sellers Section --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Top Selling Products --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Selling Products</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Best performing products by revenue</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topSellingProducts as $item)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            @if($item->product)
                                <img src="{{ Storage::url($item->product->thumbnail_path) }}" 
                                     alt="{{ $item->product->title }}" 
                                     class="h-12 w-12 rounded object-cover flex-shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item->product->title }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->sales_count }} {{ Str::plural('sale', $item->sales_count) }}
                                    </p>
                                </div>
                            @else
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-500 italic">
                                        Product deleted
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($item->total_sales, 2) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-600 dark:text-gray-400">
                        No sales data available for this period.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Performing Sellers --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Performing Sellers</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Highest earning sellers</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topPerformingSellers as $item)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            @if($item->seller)
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 flex-shrink-0">
                                    <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                        {{ strtoupper(substr($item->seller->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white truncate">
                                        {{ $item->seller->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                        {{ $item->seller->email }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        {{ $item->sales_count }} {{ Str::plural('sale', $item->sales_count) }}
                                    </p>
                                </div>
                            @else
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-gray-500 dark:text-gray-500 italic">
                                        Seller account deleted
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($item->total_earnings, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">earnings</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-600 dark:text-gray-400">
                        No seller data available for this period.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            let revenueChart = null;
            let transactionChart = null;

            function initCharts() {
                const chartData = @json($chartData);
                
                // Destroy existing charts if they exist
                if (revenueChart) {
                    revenueChart.destroy();
                }
                if (transactionChart) {
                    transactionChart.destroy();
                }

                // Revenue Chart
                const revenueCtx = document.getElementById('revenueChart');
                if (revenueCtx) {
                    revenueChart = new Chart(revenueCtx, {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Platform Revenue ($)',
                                data: chartData.revenue,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Revenue: $' + context.parsed.y.toFixed(2);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '$' + value.toFixed(0);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Transaction Chart
                const transactionCtx = document.getElementById('transactionChart');
                if (transactionCtx) {
                    transactionChart = new Chart(transactionCtx, {
                        type: 'bar',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Transactions',
                                data: chartData.transactionCounts,
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Transactions: ' + context.parsed.y;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Initialize charts on page load
            initCharts();

            // Re-initialize charts when Livewire updates
            Livewire.hook('morph.updated', () => {
                setTimeout(() => {
                    initCharts();
                }, 100);
            });
        });
    </script>
    @endpush
</div>
