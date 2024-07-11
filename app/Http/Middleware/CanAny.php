<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CanAny
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$abilities)
    {
        foreach ($abilities as $ability) {
            if (Gate::allows('Super Admin') || Gate::allows($ability)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized.');
    }
}
