<?php

namespace App\Providers;


use App\Models\Entities\Container\Container;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Inventory\InventoryItem;
use App\Models\Entities\Inventory\InventoryLeftover;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Observers\ContainerObserver;
use App\Observers\ContainerRegisterObserver;
use App\Observers\DocumentObserver;
use App\Observers\InventoryItemObserver;
use App\Observers\InventoryLeftoverObserver;
use App\Observers\LeftoverObserver;
use App\Observers\RowObserver;
use App\Observers\SectorObserver;
use App\Observers\WarehouseZoneObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    #[\Override]
    public function boot()
    {
        Leftover::observe(LeftoverObserver::class);
        ContainerRegister::observe(ContainerRegisterObserver::class);
        Document::observe(DocumentObserver::class);
        InventoryLeftover::observe(InventoryLeftoverObserver::class);
        InventoryItem::observe(InventoryItemObserver::class);
        Container::observe(ContainerObserver::class);
        WarehouseZone::observe(WarehouseZoneObserver::class);
        Row::observe(RowObserver::class);
        Sector::observe(SectorObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    #[\Override]
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
