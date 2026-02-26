<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class RedirectOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->new_user) {
            return redirect()->route('onboarding');
        }
        return $next($request);
    }
}
