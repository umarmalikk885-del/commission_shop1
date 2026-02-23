<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Set locale early in the middleware stack to ensure it's available for all requests
        $middleware->web(prepend: [
            \App\Http\Middleware\SetApplicationLocale::class,
        ]);

        // Prevent browser back button history for protected routes
        $middleware->appendToGroup('web', \App\Http\Middleware\PreventBackHistory::class);

        // Ensure authentication middleware aliases are registered so that
        // Route::middleware(['auth', 'guest', ...]) works correctly across the site.
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'role.check' => \App\Http\Middleware\CheckUserRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Redirect unauthenticated users to login page
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'));
        });
    })->create();
