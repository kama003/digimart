<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Policies\ProductPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use App\Policies\WithdrawalPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register CartService as singleton
        $this->app->singleton(\App\Services\CartService::class);
        
        // Register StorageService as singleton
        $this->app->singleton(\App\Services\StorageService::class);
        
        // Register NotificationService as singleton
        $this->app->singleton(\App\Services\NotificationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register authorization policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Transaction::class, TransactionPolicy::class);
        Gate::policy(Withdrawal::class, WithdrawalPolicy::class);

        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\PurchaseCompleted::class,
            \App\Listeners\ProcessPurchase::class,
        );

        // Enable query logging in development to identify slow queries
        if (config('app.debug')) {
            \Illuminate\Support\Facades\DB::listen(function ($query) {
                // Log queries that take longer than 100ms
                if ($query->time > 100) {
                    \Illuminate\Support\Facades\Log::channel('daily')->warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms',
                    ]);
                }
            });
        }
    }
}
