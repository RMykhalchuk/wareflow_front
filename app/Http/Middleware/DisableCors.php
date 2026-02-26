<?php

namespace App\Http\Middleware;

use Closure;

final class DisableCors
{
    public function handle($request, Closure $next)
    {
        // Bypass CORS
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Headers', '*')
            ->header('Access-Control-Allow-Methods', '*');
    }
}
