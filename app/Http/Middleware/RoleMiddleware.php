<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            Log::channel('security')->warning('Unauthorized access attempt - Not authenticated', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'timestamp' => now()->toIso8601String(),
            ]);
            abort(403, 'Unauthorized access.');
        }

        $user = Auth::user();

        // Check if user is banned
        if ($user->is_banned) {
            Log::channel('security')->warning('Banned user attempted access', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'timestamp' => now()->toIso8601String(),
            ]);
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            abort(403, 'Your account has been banned.');
        }

        // Check if user has one of the required roles
        if (!in_array($user->role->value, $roles)) {
            Log::channel('security')->warning('Unauthorized role access attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'user_role' => $user->role->value,
                'required_roles' => $roles,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'timestamp' => now()->toIso8601String(),
            ]);
            
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
