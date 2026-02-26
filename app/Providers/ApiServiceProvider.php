<?php

namespace App\Providers;

use App\Http\Requests\Api\Package\PackageService;
use App\Http\Requests\Api\Package\PackageServiceInterface;
use App\Services\Api\Auth\PinAuthService;
use App\Services\Api\Auth\PinAuthServiceInterface;
use App\Services\Api\Goods\GoodsService;
use App\Services\Api\Goods\GoodsServiceInterface;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PinAuthServiceInterface::class, PinAuthService::class);
        $this->app->bind(GoodsServiceInterface::class, GoodsService::class);
        $this->app->bind(PackageServiceInterface::class, PackageService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        RateLimiter::for('pin', function (Request $request) {
            return [ Limit::perMinute(10)->by('pin:'.$request->input('id').'|'.$request->ip()) ];
        });
    }
}
