<?php

namespace App\Providers;

use App\Services\Terminal\Completing\PickingService;
use App\Services\Terminal\Completing\PickingServiceInterface;
use App\Services\Terminal\Leftovers\LeftoverService;
use App\Services\Terminal\Leftovers\LeftoverServiceInterface;
use App\Services\Terminal\Task\Income\IncomeTaskInterface;
use App\Services\Terminal\Task\Income\IncomeTaskService;
use App\Services\Terminal\Task\ManualIncome\ManualIncomeService;
use App\Services\Terminal\Task\ManualIncome\ManualIncomeServiceInterface;
use Illuminate\Support\ServiceProvider;

class TerminalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(LeftoverServiceInterface::class, LeftoverService::class);
        $this->app->bind(IncomeTaskInterface::class, IncomeTaskService::class);
        $this->app->bind(ManualIncomeServiceInterface::class, ManualIncomeService::class);
        $this->app->bind(PickingServiceInterface::class, PickingService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
