<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated, active, and has admin access permission
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if user is active
        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')->with('error', 'Your account is inactive.');
        }

        // Check if user has admin access permission
        if (! $user->can('admin.access')) {
            return abort(403, 'Unauthorized. You do not have access to the admin panel.');
        }

        return $next($request);
    }
}
