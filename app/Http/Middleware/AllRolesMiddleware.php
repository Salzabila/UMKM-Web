<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AllRolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            Log::warning('AllRolesMiddleware: User not authenticated');
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        Log::info('AllRolesMiddleware: Checking access for user', [
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role
        ]);

        // Check if user has any of the allowed roles
        if ($user->isAdmin() || $user->isKasir() || $user->isManajer()) {
            Log::info('AllRolesMiddleware: Access granted for role: ' . $user->role);
            return $next($request);
        }

        Log::warning('AllRolesMiddleware: Access denied for role: ' . $user->role);
        return redirect('/error-unauthorized')->with('error', 'Anda tidak memiliki akses untuk fitur ini.');
    }
}
