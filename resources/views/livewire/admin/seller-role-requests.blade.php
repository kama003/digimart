<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Seller Role Requests</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review and approve seller role requests from customers</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Requests Table --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Requested
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Processed
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                            {{ $request->user->initials() }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $request->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge 
                                    :color="match($request->status) {
                                        'pending' => 'yellow',
                                        'approved' => 'green',
                                        'rejected' => 'red',
                                        default => 'gray'
                                    }"
                                    size="sm"
                                >
                                    {{ ucfirst($request->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $request->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $request->processed_at ? $request->processed_at->format('M d, Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($request->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button 
                                            size="sm" 
                                            variant="primary"
                                            wire:click="openApproveModal({{ $request->id }})"
                                        >
                                            Approve
                                        </flux:button>
                                        <flux:button 
                                            size="sm" 
                                            variant="danger"
                                            wire:click="openRejectModal({{ $request->id }})"
                                        >
                                            Reject
                                        </flux:button>
                                    </div>
                                @elseif($request->status === 'rejected' && $request->admin_notes)
                                    <button 
                                        type="button"
                                        class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                                        title="{{ $request->admin_notes }}"
                                    >
                                        View reason
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm">No seller role requests found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    {{-- Approve Modal --}}
    <flux:modal name="approve-request" :open="$showApproveModal" wire:model="showApproveModal">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Approve Seller Role Request</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Are you sure you want to approve this seller role request? The user will be upgraded to a seller account and will be able to upload and sell products.
            </p>
            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeModals">Cancel</flux:button>
                <flux:button variant="primary" wire:click="approve" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="approve">Approve</span>
                    <span wire:loading wire:target="approve">Approving...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Reject Modal --}}
    <flux:modal name="reject-request" :open="$showRejectModal" wire:model="showRejectModal">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Reject Seller Role Request</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                Please provide a reason for rejecting this seller role request. The user will be able to see this reason.
            </p>
            
            <div class="mb-6">
                <flux:textarea 
                    wire:model="rejectionReason" 
                    label="Rejection Reason"
                    placeholder="Enter the reason for rejection..."
                    rows="4"
                />
                @error('rejectionReason')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="closeModals">Cancel</flux:button>
                <flux:button variant="danger" wire:click="reject" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="reject">Reject</span>
                    <span wire:loading wire:target="reject">Rejecting...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
