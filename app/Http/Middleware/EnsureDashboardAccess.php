<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Being logged in is not enough to enter the dashboard: the account needs the
 * "view-dashboard" permission (all staff roles have it). Accounts created by
 * the old open registration form have no roles, so they are turned away here.
 */
class EnsureDashboardAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! $user->can('view-dashboard')) {
            abort(403, 'Access denied. Your account has no dashboard permissions.');
        }

        return $next($request);
    }
}
