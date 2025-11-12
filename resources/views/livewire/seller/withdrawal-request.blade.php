<div class="space-y-6">
    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Request Withdrawal</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Withdraw your earnings to your preferred payment method</p>
    </div>

    {{-- Available Balance Card --}}
    <div class="p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Available Balance</p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                    ${{ number_format($availableBalance, 2) }}
                </p>
            </div>
            <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Withdrawal Form --}}
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Withdrawal Details</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Fill in the details below to request a withdrawal</p>
        </div>

        <form wire:submit="submit" class="space-y-6 p-6">
            {{-- Amount Field --}}
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount *</label>
                <input 
                    wire:model.blur="amount" 
                    id="amount" 
                    type="number" 
                    step="0.01" 
                    min="0.01"
                    placeholder="0.00"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm" />
                @error('amount')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Maximum: ${{ number_format($availableBalance, 2) }}
                </p>
            </div>

            {{-- Method Field --}}
            <div>
                <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Withdrawal Method *</label>
                <select wire:model="method" id="method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                    <option value="">Select a method</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="paypal">PayPal</option>
                    <option value="other">Other</option>
                </select>
                @error('method')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Account Details Field --}}
            <div>
                <label for="account_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Account Details *</label>
                <textarea 
                    wire:model="account_details" 
                    id="account_details" 
                    rows="4"
                    placeholder="Enter your account details (e.g., bank account number, PayPal email, etc.)"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"></textarea>
                @error('account_details')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Provide complete details for receiving payment. This information will be encrypted.
                </p>
            </div>

            {{-- Important Notice --}}
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg dark:bg-yellow-900/20 dark:border-yellow-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Important Information</h3>
                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                            <ul class="list-disc space-y-1 pl-5">
                                <li>Withdrawal requests are reviewed by our admin team</li>
                                <li>Processing typically takes 3-5 business days</li>
                                <li>You will be notified once your request is approved or rejected</li>
                                <li>Ensure your account details are accurate to avoid delays</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('seller.withdrawals') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                    <span wire:loading.remove wire:target="submit">Submit Request</span>
                    <span wire:loading wire:target="submit">Submitting...</span>
                </button>
            </div>
        </form>
    </div>
</div>
