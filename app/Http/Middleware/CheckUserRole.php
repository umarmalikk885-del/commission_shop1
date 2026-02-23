<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     * Enforces role-based access control:
     * - Super Admin: Full access including role management
     * - Admin: Full access except role management
     * - User: Limited access (dashboard, view sales, view purchases, view stock only)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        if (!$user) {
            return redirect('/login');
        }

        $routeName = $request->route()?->getName();

        if (!$routeName) {
            return $next($request);
        }

        // Super Admin has access to everything
        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        // Admin has access to everything except role management
        if ($user->hasRole('Admin')) {
            // Block role management routes
            $restrictedRoutes = [
                'settings.roles',
                'roles.create',
                'roles.update',
                'roles.delete',
            ];

            if (in_array($routeName, $restrictedRoutes, true)) {
                return redirect('/settings')
                    ->with('error', 'You do not have permission to manage roles. Only Super Admin can access this feature.');
            }

            return $next($request);
        }

        // User role: Only allow specific routes based on permissions
        // Users can only access routes they have explicit permissions for
        if ($user->hasRole('User')) {
            // Routes that Users can potentially access (subject to permission checks)
            $allowedUserRoutes = [
                // Dashboard - always accessible
                'dashboard',
                
                // Profile routes - accessible to all authenticated users
                'profile.edit',
                'profile.update',
                'profile.destroy',

                // Settings main page (Users can open it, but only see language card)
                'settings',

                // Language switch endpoint (used by Settings page language dropdown)
                'language.switch',
                
                // Sales routes (requires 'view sales' permission to create/update/delete)
                'sales',
                'sales.store',
                'sales.print',
                'sales.json',
                'sales.destroy',
                
                // Purchase routes (requires 'view purchases' permission to create/update/delete)
                'purchase',
                'purchase.store',
                'purchase.edit',
                'purchase.update',
                'purchase.print',
                'purchase.items',
                'purchase.destroy',
                
                // Stock routes (view only - require 'view stock' permission)
                'stock',
                'stock.updates',
                'stock.bakery',
                
                // Dues routes - available to all Users
                'dues',
                'dues.selling',
                'dues.clear',
                'dues.payment',
                'dues.loans.store',
                'dues.loans.payment',
                'dues.selling',
                
                // Product Owner - available to all Users
                'product-owner',
                'product-owner.loans.store',
                'product-owner.loans.payment',
                
                // Laga Details routes - available to all Users
                'laga-details',
                'laga-details.show',
                
                // Recovery routes - available to all Users
                'recovery',
                'recovery.store',
                
                // Reports routes (Admin and Super Admin only)
                'reports',
                'reports.search-purchasers',
                'reports.sales',
                'reports.purchases',
                'reports.expenses',
                'reports.profit-loss',
                'reports.dues',
                'reports.balance-sheet',
                
                // Rokad routes (Admin and Super Admin only)
                'rokad',
                'rokad.store',
                'rokad.update',
                'rokad.destroy',
                
                // Backup routes (Admin and Super Admin only)
                'backup',
                'backup.create',
                'backup.download',
                
                // Bank/Cash routes (Admin and Super Admin only)
                'bank-cash',
                'bank-cash.index',
                'bank-cash.store',
                'bank-cash.update',
                'bank-cash.destroy',
            ];

            // Check if route is in the allowed list
            if (in_array($routeName, $allowedUserRoutes, true)) {
                // Additional permission check for view routes
                // Even if route is allowed, user must have the specific permission
                if (str_starts_with($routeName, 'sales') && !$user->hasPermissionTo('view sales')) {
                    return redirect('/dashboard')
                        ->with('error', 'You do not have permission to view sales.');
                }
                
                if (str_starts_with($routeName, 'purchase') && !$user->hasPermissionTo('view purchases')) {
                    return redirect('/dashboard')
                        ->with('error', 'You do not have permission to view purchases.');
                }
                
                if (str_starts_with($routeName, 'stock') && !$user->hasPermissionTo('view stock')) {
                    return redirect('/dashboard')
                        ->with('error', 'You do not have permission to view stock.');
                }

                // Route is allowed and user has required permission
                return $next($request);
            }

            // User tries to access a route that is not in the allowed list
            // This prevents Users from accessing any unauthorized pages even by typing the URL
            return redirect('/dashboard')
                ->with('error', 'You do not have permission to access this page. Please contact your administrator.');
        }

        // Operator role (if exists) - keep existing behavior
        if ($user->hasRole('Operator')) {
            $allowedOperatorRoutes = [
                'dashboard',
                // Profile routes - accessible to all authenticated users
                'profile.edit',
                'profile.update',
                'profile.destroy',
                'sales',
                'sales.store',
                'sales.print',
                'sales.json',
                'sales.destroy',
                'purchase',
                'purchase.store',
                'purchase.edit',
                'purchase.update',
                'purchase.destroy',
                'purchase.print',
                'purchase.items',
                'dues',
                'dues.clear',
                'dues.payment',
                'dues.loans.store',
                'dues.loans.payment',
                'dues.selling',
                'product-owner.loans.payment',
                'stock.bakery',
                'stock.updates',
                
                // Laga Details routes - available to Operators
                'laga-details',
                'laga-details.show',
            ];

            if (in_array($routeName, $allowedOperatorRoutes, true)) {
                return $next($request);
            }

            return redirect('/dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        // Default: deny access for unknown roles
        return redirect('/dashboard')
            ->with('error', 'You do not have permission to access this page.');
    }
}
