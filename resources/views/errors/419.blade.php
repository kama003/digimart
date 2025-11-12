<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Expired - {{ config('app.name', 'Digital Marketplace') }}</title>
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
                <svg class="mx-auto h-24 w-24 sm:h-32 sm:w-32 text-amber-400 dark:text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl sm:text-7xl font-bold text-zinc-900 dark:text-white mb-4">419</h1>
            
            <!-- Error Message -->
            <h2 class="text-2xl sm:text-3xl font-semibold text-zinc-800 dark:text-zinc-200 mb-4">
                Session Expired
            </h2>
            
            <p class="text-base sm:text-lg text-zinc-600 dark:text-zinc-400 mb-8">
                Your session has expired due to inactivity. Please refresh the page to continue.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh Page
                </button>
                
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Go to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
