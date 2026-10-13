
<?php

use App\Http\Middleware\EnsureCustomerIsAuthenticated;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'role' => EnsureUserHasRole::class,
            'customer.authenticated' => EnsureCustomerIsAuthenticated::class,
        ]);

        $middleware->redirectGuestsTo(
            fn (Request $request): string => route('admin.login')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
