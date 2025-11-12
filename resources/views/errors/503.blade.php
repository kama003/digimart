<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance Mode - {{ config('app.name', 'Digital Marketplace') }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="antialiased bg-zinc-50 dark:bg-zinc-900">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center">
            <!-- Error Icon -->
            <div class="mb-6 sm:mb-8">
                <svg class="mx-auto h-24 w-24 sm:h-32 sm:w-32 text-blue-400 dark:text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl sm:text-7xl font-bold text-zinc-900 dark:text-white mb-4">503</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl sm:text-3xl font-semibold text-zinc-800 dark:text-zinc-200 mb-4">
                Under Maintenance
            </h2>
            
            <p class="text-base sm:text-lg text-zinc-600 dark:text-zinc-400 mb-8">
                We're currently performing scheduled maintenance to improve your experience. We'll be back shortly!
            </p>

            @if(isset($exception) && $exception->getMessage())
                <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        {{ $exception->getMessage() }}
                    </p>
                </div>
            @endif

            <!-- Action Button -->
            <div class="flex justify-center">
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Try Again
                </button>
            </div>
        </div>
    </div>
</body>
</html>
