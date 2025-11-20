<div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
    <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('about') }}" class="hover:text-gray-900 dark:hover:text-gray-200 transition-colors" wire:navigate>About</a>
        <span class="text-gray-400">•</span>
        <a href="{{ route('contact') }}" class="hover:text-gray-900 dark:hover:text-gray-200 transition-colors" wire:navigate>Contact</a>
        <span class="text-gray-400">•</span>
        <a href="{{ route('terms') }}" class="hover:text-gray-900 dark:hover:text-gray-200 transition-colors" wire:navigate>Terms</a>
        <span class="text-gray-400">•</span>
        <a href="{{ route('privacy') }}" class="hover:text-gray-900 dark:hover:text-gray-200 transition-colors" wire:navigate>Privacy</a>
    </div>
    <div class="text-center text-sm text-gray-500 dark:text-gray-500 mt-4">
        &copy; {{ date('Y') }} {{ config('app.name', 'Digital Marketplace') }}. All rights reserved.
    </div>
</div>
