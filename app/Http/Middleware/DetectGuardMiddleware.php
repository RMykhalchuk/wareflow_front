<?php

namespace App\Http\Middleware;

use App\Services\Web\Auth\GuardContext;
use Closure;
use Illuminate\Http\Request;


class DetectGuardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Визначаємо guard
        $guard = $this->detectGuard($request);

        // 2. Заносимо в глобальний контекст
        app(GuardContext::class)->setGuard($guard);

        return $next($request);
    }

    private function detectGuard(Request $request): string
    {
        if ($request->is('api/terminal/*')) {
            return 'terminal';
        }

        if ($request->is('api/*')) {
            return 'api';
        }

        return 'web';
    }
}
