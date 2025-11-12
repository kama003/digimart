<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Analytics</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Track your sales performance and revenue trends</p>
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
        </div>
    </div>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        {{-- Total Sales --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Sales</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($totalSales) }}
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    All-time transactions
                </p>
            </div>
        </div>

        {{-- Average Order Value --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Order Value</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                    ${{ number_format($averageOrderValue, 2) }}
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Per transaction
                </p>
            </div>
        </div>

        {{-- Conversion Rate --}}
        <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Product Conversion Rate</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($conversionRate, 1) }}%
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Products with sales
                </p>
            </div>
        </div>
    </div>

    {{-- Revenue Trend Chart --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue Trend</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Your earnings over time ({{ ucfirst($period) }})
            </p>
        </div>
        <div class="p-6">
            <div wire:ignore>
                <canvas id="revenueChart" class="h-64"></canvas>
            </div>
        </div>
    </div>

    {{-- Sales Count Chart --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Volume</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Number of transactions over time ({{ ucfirst($period) }})
            </p>
        </div>
        <div class="p-6">
            <div wire:ignore>
                <canvas id="salesChart" class="h-64"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            let revenueChart = null;
            let salesChart = null;

            function initCharts() {
                const chartData = @json($chartData);
                
                // Destroy existing charts if they exist
                if (revenueChart) {
                    revenueChart.destroy();
                }
                if (salesChart) {
                    salesChart.destroy();
                }

                // Revenue Chart
                const revenueCtx = document.getElementById('revenueChart');
                if (revenueCtx) {
                    revenueChart = new Chart(revenueCtx, {
                        type: 'line',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Revenue ($)',
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

                // Sales Chart
                const salesCtx = document.getElementById('salesChart');
                if (salesCtx) {
                    salesChart = new Chart(salesCtx, {
                        type: 'bar',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Sales Count',
                                data: chartData.sales,
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
                                            return 'Sales: ' + context.parsed.y;
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
