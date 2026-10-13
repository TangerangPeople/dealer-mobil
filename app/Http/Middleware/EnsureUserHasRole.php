<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user('web');

        abort_unless(
            $user !== null && $user->hasAnyRole($roles),
            403
        );

        return $next($request);
    }
}
