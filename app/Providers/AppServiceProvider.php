<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request): Limit {
            $email = $request->input('email');
            $identifier = is_string($email) ? Str::lower(trim($email)) : '';

            return Limit::perMinute(5)->by($identifier.'|'.$request->ip());
        });

        RateLimiter::for('customer-login', function (Request $request): Limit {
            $email = $request->input('email');
            $identifier = is_string($email) ? Str::lower(trim($email)) : '';

            return Limit::perMinute(5)->by($identifier.'|'.$request->ip());
        });

        RateLimiter::for('customer-register', function (Request $request): Limit {
            $email = $request->input('email');
            $identifier = is_string($email) ? Str::lower(trim($email)) : '';

            return Limit::perMinute(5)->by($identifier.'|'.$request->ip());
        });
    }
}
