<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Withdrawal History</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View all your withdrawal requests and their status</p>
        </div>
        <a href="{{ route('seller.withdrawals.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
            New Withdrawal
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Withdrawals Table --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Date Requested
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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Processed Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Notes
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                    @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $withdrawal->created_at->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                ${{ number_format($withdrawal->amount, 2) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ ucfirst(str_replace('_', ' ', $withdrawal->method)) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                @if($withdrawal->status === 'pending')
                                    <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Pending
                                    </span>
                                @elseif($withdrawal->status === 'approved')
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Approved
                                    </span>
                                @elseif($withdrawal->status === 'completed')
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Completed
                                    </span>
                                @elseif($withdrawal->status === 'rejected')
                                    <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $withdrawal->processed_at?->format('M d, Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                @if($withdrawal->admin_notes)
                                    <div class="max-w-xs truncate" title="{{ $withdrawal->admin_notes }}">
                                        {{ $withdrawal->admin_notes }}
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <p class="mt-4 text-gray-600 dark:text-gray-400">No withdrawal requests yet</p>
                                    <a href="{{ route('seller.withdrawals.create') }}" class="inline-flex items-center px-3 py-2 mt-4 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                        Request Your First Withdrawal
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($withdrawals->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>
