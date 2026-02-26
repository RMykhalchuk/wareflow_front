<?php

namespace App\Http\Middleware;

use App\Services\Web\Company\CompanyContextService;
use Closure;
use Illuminate\Http\Request;

class SetCompanyContext
{
    public function handle(Request $request, Closure $next)
    {
        CompanyContextService::apply();

        return $next($request);
    }
}
