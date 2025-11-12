<!-- Toast Notification Container -->
<div 
    aria-live="assertive" 
    class="pointer-events-none fixed inset-0 z-50 flex items-end px-4 py-6 sm:items-start sm:p-6"
>
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
        @if (session('success'))
            <x-toast type="success" :message="session('success')" />
        @endif

        @if (session('error'))
            <x-toast type="error" :message="session('error')" />
        @endif

        @if (session('warning'))
            <x-toast type="warning" :message="session('warning')" />
        @endif

        @if (session('info'))
            <x-toast type="info" :message="session('info')" />
        @endif
    </div>
</div>
