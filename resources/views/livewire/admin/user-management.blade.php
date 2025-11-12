<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage all registered users and their roles</p>
    </div>

    {{-- Filters --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" role="search" aria-label="User filters">
        <div class="flex-1 max-w-md">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                type="search" 
                placeholder="Search by name or email..."
                icon="magnifying-glass"
                aria-label="Search users by name or email"
            />
        </div>

        <div class="w-full sm:w-48">
            <flux:select wire:model.live="role_filter" placeholder="All Roles" aria-label="Filter users by role">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg" role="region" aria-label="Users table">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Balance
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Joined
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
                                            {{ $user->initials() }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <flux:badge 
                                    :color="match($user->role->value) {
                                        'admin' => 'red',
                                        'seller' => 'blue',
                                        'customer' => 'gray',
                                        default => 'gray'
                                    }"
                                    size="sm"
                                >
                                    {{ ucfirst($user->role->value) }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_banned)
                                    <flux:badge color="red" size="sm">Banned</flux:badge>
                                @else
                                    <flux:badge color="green" size="sm">Active</flux:badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${{ number_format($user->balance, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if(auth()->id() !== $user->id)
                                        <flux:button 
                                            size="sm" 
                                            variant="ghost"
                                            wire:click="openChangeRoleModal({{ $user->id }})"
                                        >
                                            Change Role
                                        </flux:button>
                                        <flux:button 
                                            size="sm" 
                                            :variant="$user->is_banned ? 'primary' : 'danger'"
                                            wire:click="openBanModal({{ $user->id }})"
                                        >
                                            {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                        </flux:button>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">You</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm">No users found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Ban/Unban Modal --}}
    <flux:modal name="ban-user" :open="$showBanModal" wire:model="showBanModal">
        <div class="p-6">
            @if($selectedUser)
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $selectedUser->is_banned ? 'Unban' : 'Ban' }} User
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                    @if($selectedUser->is_banned)
                        Are you sure you want to unban <strong>{{ $selectedUser->name }}</strong>? They will be able to access the platform again.
                    @else
                        Are you sure you want to ban <strong>{{ $selectedUser->name }}</strong>? They will be logged out immediately and will not be able to access the platform.
                    @endif
                </p>
                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" wire:click="closeModals">Cancel</flux:button>
                    <flux:button 
                        :variant="$selectedUser->is_banned ? 'primary' : 'danger'" 
                        wire:click="toggleBan" 
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="toggleBan">
                            {{ $selectedUser->is_banned ? 'Unban User' : 'Ban User' }}
                        </span>
                        <span wire:loading wire:target="toggleBan">Processing...</span>
                    </flux:button>
                </div>
            @endif
        </div>
    </flux:modal>

    {{-- Change Role Modal --}}
    <flux:modal name="change-role" :open="$showChangeRoleModal" wire:model="showChangeRoleModal">
        <div class="p-6">
            @if($selectedUser)
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Change User Role</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Change the role for <strong>{{ $selectedUser->name }}</strong>. This will affect their permissions and access to features.
                </p>
                
                <div class="mb-6">
                    <flux:select wire:model="newRole" label="New Role">
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                        @endforeach
                    </flux:select>
                    
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            <strong>Current role:</strong> {{ ucfirst($selectedUser->role->value) }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" wire:click="closeModals">Cancel</flux:button>
                    <flux:button variant="primary" wire:click="changeRole" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="changeRole">Change Role</span>
                        <span wire:loading wire:target="changeRole">Changing...</span>
                    </flux:button>
                </div>
            @endif
        </div>
    </flux:modal>
</div>
