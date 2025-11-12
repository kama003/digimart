@props(['id', 'title' => 'Confirm Action', 'message' => 'Are you sure you want to proceed?', 'confirmText' => 'Confirm', 'cancelText' => 'Cancel', 'confirmAction'])

<div 
    x-data="{ show: false }"
    x-on:open-modal-{{ $id }}.window="show = true"
    x-on:close-modal-{{ $id }}.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title-{{ $id }}"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div 
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-zinc-900/50 backdrop-blur-sm transition-opacity"
        @click="show = false"
        aria-hidden="true"
    ></div>

    <!-- Modal -->
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-lg bg-white dark:bg-zinc-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
            @click.away="show = false"
        >
            <div class="bg-white dark:bg-zinc-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Warning Icon -->
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    
                    <!-- Content -->
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-zinc-900 dark:text-white" id="modal-title-{{ $id }}">
                            {{ $title }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-zinc-50 dark:bg-zinc-900 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                <button 
                    type="button"
                    wire:click="{{ $confirmAction }}"
                    @click="show = false"
                    class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 sm:w-auto transition-colors"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="{{ $confirmAction }}">{{ $confirmText }}</span>
                    <span wire:loading wire:target="{{ $confirmAction }}">Processing...</span>
                </button>
                <button 
                    type="button"
                    @click="show = false"
                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-zinc-800 px-4 py-2.5 text-sm font-semibold text-zinc-900 dark:text-white shadow-sm ring-1 ring-inset ring-zinc-300 dark:ring-zinc-600 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900 sm:mt-0 sm:w-auto transition-colors"
                >
                    {{ $cancelText }}
                </button>
            </div>
        </div>
    </div>
</div>
