<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Purchase History</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View and download your purchased digital products</p>
    </div>

    @if (session('success'))
        <flux:banner variant="success" class="mb-6">
            {{ session('success') }}
        </flux:banner>
    @endif

    @if (session('error'))
        <flux:banner variant="danger" class="mb-6">
            {{ session('error') }}
        </flux:banner>
    @endif

    @if ($transactions->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No purchases yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Start browsing our marketplace to find amazing digital products.</p>
            <div class="mt-6">
                <flux:button href="{{ route('home') }}" variant="primary">
                    Browse Products
                </flux:button>
            </div>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($transactions as $transaction)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <!-- Transaction Header -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Order #{{ $transaction->id }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($transaction->amount, 2) }}
                                </span>
                                <flux:badge variant="success" size="sm">
                                    {{ ucfirst($transaction->status) }}
                                </flux:badge>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Items -->
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($transaction->transactionItems as $item)
                            @php
                                $product = $item->product;
                                $downloadKey = $product->id . '_' . $transaction->id;
                                $activeDownload = $activeDownloads->get($downloadKey);
                            @endphp
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <!-- Product Thumbnail -->
                                    <div class="flex-shrink-0">
                                        <img 
                                            src="{{ Storage::disk('public')->url($product->thumbnail_path) }}" 
                                            alt="{{ $product->title }}"
                                            class="w-full sm:w-24 h-24 object-cover rounded-lg"
                                            loading="lazy"
                                        >
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    <a href="{{ route('product.show', $product->slug) }}" class="hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $product->title }}
                                                    </a>
                                                </h3>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $product->category->name }} • {{ ucfirst(str_replace('_', ' ', $product->product_type)) }}
                                                </p>
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                                    By {{ $product->user->name }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-medium text-gray-900 dark:text-white">
                                                    ${{ number_format($item->price, 2) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Download Actions -->
                                        <div class="mt-4 flex flex-col sm:flex-row gap-2">
                                            @if ($activeDownload)
                                                <flux:button 
                                                    href="{{ route('download', $activeDownload->id) }}" 
                                                    variant="primary"
                                                    size="sm"
                                                    target="_blank"
                                                >
                                                    <flux:icon.arrow-down-tray class="w-4 h-4" />
                                                    Download Now
                                                </flux:button>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                    <flux:icon.clock class="w-4 h-4 mr-1" />
                                                    Expires {{ $activeDownload->expires_at->diffForHumans() }}
                                                </p>
                                            @else
                                                <flux:button 
                                                    wire:click="generateDownloadLink({{ $product->id }}, {{ $transaction->id }})"
                                                    variant="outline"
                                                    size="sm"
                                                    wire:loading.attr="disabled"
                                                    wire:target="generateDownloadLink({{ $product->id }}, {{ $transaction->id }})"
                                                >
                                                    <span wire:loading.remove wire:target="generateDownloadLink({{ $product->id }}, {{ $transaction->id }})">
                                                        <flux:icon.arrow-path class="w-4 h-4" />
                                                        Generate Download Link
                                                    </span>
                                                    <span wire:loading wire:target="generateDownloadLink({{ $product->id }}, {{ $transaction->id }})">
                                                        <flux:icon.arrow-path class="w-4 h-4 animate-spin" />
                                                        Generating...
                                                    </span>
                                                </flux:button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
