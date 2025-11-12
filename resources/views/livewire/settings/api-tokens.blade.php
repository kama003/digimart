<div>
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('API Tokens')" :subheading="__('Manage API tokens for accessing your account programmatically')">
        <div class="space-y-6 w-full max-w-4xl">

        <!-- Create Token Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">Create New Token</h3>
            
            <form wire:submit="createToken" class="space-y-4">
                <div>
                    <label for="tokenName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Token Name
                    </label>
                    <input 
                        type="text" 
                        id="tokenName"
                        wire:model="tokenName"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 sm:text-sm"
                        placeholder="e.g., Mobile App Token"
                    >
                    @error('tokenName')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button 
                        type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="createToken">Create Token</span>
                        <span wire:loading wire:target="createToken">Creating...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Token Created Modal -->
        @if($plainTextToken)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Token Created Successfully
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Please copy your new API token. For security reasons, it won't be shown again.
                                </p>
                            </div>
                            <div class="mt-4">
                                <div class="bg-gray-50 dark:bg-gray-900 rounded-md p-4">
                                    <code class="text-sm text-gray-900 dark:text-gray-100 break-all">{{ $plainTextToken }}</code>
                                </div>
                                <button 
                                    type="button"
                                    onclick="navigator.clipboard.writeText('{{ $plainTextToken }}')"
                                    class="mt-3 inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Copy to Clipboard
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-6">
                        <button 
                            type="button"
                            wire:click="closeTokenModal"
                            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Existing Tokens List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Active Tokens</h3>
            </div>
            
            @if($tokens->isEmpty())
            <div class="px-6 py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No API tokens</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new API token.</p>
            </div>
            @else
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($tokens as $token)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                {{ $token->name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Created {{ $token->created_at->diffForHumans() }}
                                @if($token->last_used_at)
                                    • Last used {{ $token->last_used_at->diffForHumans() }}
                                @else
                                    • Never used
                                @endif
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0">
                            <button 
                                type="button"
                                wire:click="revokeToken({{ $token->id }})"
                                wire:confirm="Are you sure you want to revoke this token? This action cannot be undone."
                                class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-600 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 dark:text-red-400 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                Revoke
                            </button>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @endif
        </div>

        <!-- API Documentation Link -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                        API Documentation
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                        <p>
                            Use your API token in the Authorization header: <code class="bg-blue-100 dark:bg-blue-900 px-1 py-0.5 rounded">Bearer YOUR_TOKEN</code>
                        </p>
                        <p class="mt-2">
                            Available endpoints:
                        </p>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li><code>GET /api/v1/user/purchases</code> - Get your purchased products</li>
                            <li><code>POST /api/v1/user/downloads/{download}</code> - Generate download link</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </x-settings.layout>
</div>
