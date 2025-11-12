<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Billing History</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">View your transaction history and download invoices</p>
    </div>

    @if ($transactions->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No transactions yet</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your billing history will appear here once you make a purchase.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Transaction ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Payment Method
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    #{{ $transaction->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->format('M j, Y') }}
                                    <span class="block text-xs text-gray-400 dark:text-gray-500">
                                        {{ $transaction->created_at->format('g:i A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                    ${{ number_format($transaction->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center">
                                        @if ($transaction->payment_gateway === 'stripe')
                                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305h.003z"/>
                                            </svg>
                                            <span>Stripe</span>
                                        @elseif ($transaction->payment_gateway === 'paytm')
                                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/>
                                            </svg>
                                            <span>Paytm</span>
                                        @else
                                            <span>{{ ucfirst($transaction->payment_gateway) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($transaction->status === 'completed')
                                        <flux:badge variant="success" size="sm">Completed</flux:badge>
                                    @elseif ($transaction->status === 'pending')
                                        <flux:badge variant="warning" size="sm">Pending</flux:badge>
                                    @elseif ($transaction->status === 'failed')
                                        <flux:badge variant="danger" size="sm">Failed</flux:badge>
                                    @elseif ($transaction->status === 'refunded')
                                        <flux:badge variant="info" size="sm">Refunded</flux:badge>
                                    @else
                                        <flux:badge size="sm">{{ ucfirst($transaction->status) }}</flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($transaction->status === 'completed')
                                        <flux:button 
                                            href="{{ route('invoice.download', $transaction->id) }}" 
                                            variant="ghost"
                                            size="sm"
                                            target="_blank"
                                        >
                                            <flux:icon.arrow-down-tray class="w-4 h-4" />
                                            Download Invoice
                                        </flux:button>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">No invoice available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
