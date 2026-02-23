<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictByAppRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get current application role from CompanySettings
        $settings = \App\Models\CompanySetting::current();
        $appRole = optional($settings)->role ?? 'admin';

        $routeName = $request->route()?->getName();

        if (! $routeName) {
            return $next($request);
        }

        // Dashboard is always accessible to everyone
        if ($routeName === 'dashboard') {
            return $next($request);
        }

        // Admin has access to everything
        if ($appRole === 'admin') {
            return $next($request);
        }

        // Operator: Only allowed routes (Sales, Purchase, Dues, Advance Dues)
        if ($appRole === 'operator') {
            $allowedOperatorRoutes = [
                // Sales routes
                'sales',
                'sales.store',
                'sales.print',
                'sales.json',
                'sales.destroy',

                // Purchase routes
                'purchase',
                'purchase.store',
                'purchase.edit',
                'purchase.update',
                'purchase.destroy',
                'purchase.print',
                'purchase.items',
            ];

            if (in_array($routeName, $allowedOperatorRoutes, true)) {
                return $next($request);
            }

            // Operator tries to access restricted route
            return redirect()
                ->route('dashboard')
                ->with('error', 'You do not have permission to access this page. Only Admin can access this feature.');
        }

        // Default: allow access (for backwards compatibility)
        return $next($request);
    }
}

