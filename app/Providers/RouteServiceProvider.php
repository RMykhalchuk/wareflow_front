<?php

namespace App\Providers;

use App\Models\Entities\Container\Container;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\DocumentType;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Inventory\InventoryLeftover;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Leftover\LeftoverToContainerRegister;
use App\Models\Entities\Location;
use App\Models\Entities\Task\Task;
use App\Models\Entities\Transport\AdditionalEquipment;
use App\Models\Entities\Transport\Transport;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseErp;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Models\User;
use App\Services\Web\Company\CompanyContextService;
use Dedoc\Scramble\Scramble;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Routing\Route as RoutingRoute;

final class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = 'login';
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    #[\Override]
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        $this->registerCompanyContextBindings();

        if (app()->environment('production')) {
            Scramble::routes(function (RoutingRoute $route) {
                if (Str::startsWith($route->uri(), 'api/terminal')) {
                    return false;
                }

                return Str::startsWith($route->uri(), 'api/');
            });
        }
    }

    protected function registerCompanyContextBindings(): void
    {
        $bindings = [
            'warehouse' => Warehouse::class,
            'user' => User::class,
            'transport' => Transport::class,
            'container' => Container::class,
            'transport_equipment' => AdditionalEquipment::class,
            'sku' => Goods::class,
            'goods' => Goods::class,
            'location' => Location::class,
            'container_register' => ContainerRegister::class,
            'goods_to_container_register' => LeftoverToContainerRegister::class,
            'zone' => WarehouseZone::class,
            'additional_equipment' => AdditionalEquipment::class,
            'document' => Document::class,
            'document_type' => DocumentType::class,
            'leftover' => Leftover::class,
            'leftovers' => InventoryLeftover::class,
            'task' => Task::class,
            'warehouse_erp' => WarehouseErp::class,
            'incomeDocumentLeftover' => IncomeDocumentLeftover::class,
        ];

        foreach ($bindings as $param => $modelClass) {
            Route::bind($param, function ($value) use ($modelClass) {
                CompanyContextService::apply();

                return $modelClass::where('id', $value)->firstOrFail();
            });
        }
    }


    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
