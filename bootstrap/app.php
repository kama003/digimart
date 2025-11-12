<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Define API rate limiters
            RateLimiter::for('api-public', function (Request $request) {
                return Limit::perMinute(60)
                    ->by($request->ip())
                    ->response(function (Request $request, array $headers) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Too many requests. Please try again later.',
                            'error' => 'rate_limit_exceeded',
                        ], 429, $headers);
                    });
            });

            RateLimiter::for('api-authenticated', function (Request $request) {
                return Limit::perMinute(120)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(function (Request $request, array $headers) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Too many requests. Please try again later.',
                            'error' => 'rate_limit_exceeded',
                        ], 429, $headers);
                    });
            });

            RateLimiter::for('api-downloads', function (Request $request) {
                return Limit::perHour(10)
                    ->by($request->user()?->id ?: $request->ip())
                    ->response(function (Request $request, array $headers) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Download limit exceeded. You can generate up to 10 download links per hour.',
                            'error' => 'download_limit_exceeded',
                        ], 429, $headers);
                    });
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        
        // Exclude webhook routes from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
        ]);
        
        // Trust proxies for production (load balancers, CDN, etc.)
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
