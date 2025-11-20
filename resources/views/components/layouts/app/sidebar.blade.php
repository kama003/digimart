<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <!-- Skip to main content link for accessibility -->
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-green-600 focus:text-white focus:rounded-lg focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            Skip to main content
        </a>

        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900" role="navigation" aria-label="Main navigation">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>

                @if(auth()->user()->isAdmin())
                    <flux:navlist.group :heading="__('Admin')" class="grid">
                        <flux:navlist.item icon="chart-bar" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>{{ __('Admin Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>{{ __('User Management') }}</flux:navlist.item>
                        <flux:navlist.item icon="cube" :href="route('admin.products')" :current="request()->routeIs('admin.products*')" wire:navigate>{{ __('Product Moderation') }}</flux:navlist.item>
                        <flux:navlist.item icon="chat-bubble-left-right" :href="route('admin.reviews')" :current="request()->routeIs('admin.reviews')" wire:navigate>{{ __('Reviews') }}</flux:navlist.item>
                        <flux:navlist.item icon="document-text" :href="route('admin.seller-requests')" :current="request()->routeIs('admin.seller-requests')" wire:navigate>{{ __('Seller Requests') }}</flux:navlist.item>
                        <flux:navlist.item icon="credit-card" :href="route('admin.transactions')" :current="request()->routeIs('admin.transactions')" wire:navigate>{{ __('Transactions') }}</flux:navlist.item>
                        <flux:navlist.item icon="currency-dollar" :href="route('admin.withdrawals')" :current="request()->routeIs('admin.withdrawals')" wire:navigate>{{ __('Withdrawals') }}</flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Content')" class="grid">
                        <flux:navlist.item icon="document-text" :href="route('admin.blog')" :current="request()->routeIs('admin.blog')" wire:navigate>{{ __('Blog Moderation') }}</flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Backend Logic')" class="grid">
                        <flux:navlist.item icon="tag" :href="route('admin.categories')" :current="request()->routeIs('admin.categories')" wire:navigate>{{ __('Categories') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endif

                @if(auth()->user()->isSeller() || auth()->user()->isAdmin())
                    <flux:navlist.group :heading="__('Seller')" class="grid">
                        <flux:navlist.item icon="chart-bar" :href="route('seller.dashboard')" :current="request()->routeIs('seller.dashboard')" wire:navigate>{{ __('Seller Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="cube" :href="route('seller.products.index')" :current="request()->routeIs('seller.products*')" wire:navigate>{{ __('My Products') }}</flux:navlist.item>
                        <flux:navlist.item icon="document-text" :href="route('seller.blog.index')" :current="request()->routeIs('seller.blog*')" wire:navigate>{{ __('My Blog Posts') }}</flux:navlist.item>
                        <flux:navlist.item icon="currency-dollar" :href="route('seller.withdrawals')" :current="request()->routeIs('seller.withdrawals*')" wire:navigate>{{ __('Withdrawals') }}</flux:navlist.item>
                        <flux:navlist.item icon="chart-pie" :href="route('seller.analytics')" :current="request()->routeIs('seller.analytics')" wire:navigate>{{ __('Analytics') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                @auth
                    <flux:navlist.item icon="bell" :href="route('notifications')" wire:navigate>
                        <div class="flex items-center justify-between w-full">
                            <span>{{ __('Notifications') }}</span>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ auth()->user()->unreadNotifications()->count() > 99 ? '99+' : auth()->user()->unreadNotifications()->count() }}
                                </span>
                            @endif
                        </div>
                    </flux:navlist.item>
                @endauth

               

                <flux:navlist.item icon="code-bracket" href="/docs" target="_blank">
                {{ __('API Documentation') }}
                </flux:navlist.item>

                
            </flux:navlist>

            <!-- Desktop User Menu -->
            @auth
                <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                    <flux:profile
                        :name="auth()->user()->name"
                        :initials="auth()->user()->initials()"
                        icon:trailing="chevrons-up-down"
                        data-test="sidebar-menu-button"
                    />

                    <flux:menu class="w-[220px]">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <div class="hidden lg:block">
                    <flux:button :href="route('login')" variant="ghost" size="sm">{{ __('Log In') }}</flux:button>
                </div>
            @endauth
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <!-- Notification Bell -->
            @auth
                <div class="mr-2">
                    <livewire:notifications.notification-bell />
                    <livewire:notifications.notification-dropdown />
                </div>
            @endauth

            <!-- Cart Icon -->
            <livewire:cart.cart-icon />

            @auth
                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                    />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:button :href="route('login')" variant="ghost" size="sm">{{ __('Log In') }}</flux:button>
            @endauth
        </flux:header>

        {{ $slot }}

        <!-- Toast Notifications -->
        <x-toast-container />

        @fluxScripts
    </body>
</html>
