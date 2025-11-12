<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Withdrawal Management</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review and process seller withdrawal requests</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 dark:bg-green-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 dark:bg-red-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <label for="status_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter by Status</label>
                <select 
                    id="status_filter"
                    wire:model.live="status_filter"
                    class="p-2 w-full md:w-64 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="completed">Completed</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Seller
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Method
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Current Balance
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Requested
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($withdrawals as $withdrawal)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                #{{ $withdrawal->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $withdrawal->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $withdrawal->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                ${{ number_format($withdrawal->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white capitalize">
                                {{ str_replace('_', ' ', $withdrawal->method) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${{ number_format($withdrawal->user->balance, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$withdrawal->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                                @if($withdrawal->admin_notes)
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Note: {{ Str::limit($withdrawal->admin_notes, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $withdrawal->created_at->format('M d, Y') }}
                                @if($withdrawal->processed_at)
                                    <div class="text-xs">Processed: {{ $withdrawal->processed_at->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($withdrawal->status === 'pending')
                                    <div class="flex gap-2">
                                        <button 
                                            wire:click="approve({{ $withdrawal->id }})"
                                            wire:confirm="Are you sure you want to approve this withdrawal? The amount will be deducted from the seller's balance."
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                        >
                                            Approve
                                        </button>
                                        <button 
                                            wire:click="showRejectModal({{ $withdrawal->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">No actions</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2">No withdrawal requests found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($withdrawals->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>

    <!-- Rejection Modal -->
    @if($rejectingWithdrawalId)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cancelReject"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Reject Withdrawal Request
                                </h3>
                                <div class="mt-4">
                                    <label for="rejectionReason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Reason for Rejection <span class="text-red-500">*</span>
                                    </label>
                                    <textarea 
                                        id="rejectionReason"
                                        wire:model="rejectionReason"
                                        rows="4"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500"
                                        placeholder="Please provide a detailed reason for rejecting this withdrawal request..."
                                    ></textarea>
                                    @error('rejectionReason')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        The seller will receive this reason via email and in-app notification.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="button"
                            wire:click="reject"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Reject Withdrawal
                        </button>
                        <button 
                            type="button"
                            wire:click="cancelReject"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
