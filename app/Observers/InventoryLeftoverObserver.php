<?php

namespace App\Observers;

use App\Models\Entities\Inventory\InventoryLeftover;

/**
 * Inventory Leftover Observer.
 */
class InventoryLeftoverObserver
{
    /**
     * Handle the InventoryLeftover "created" event.
     */
    public function saving(InventoryLeftover $m): void
    {
        $m->area = $this->detectArea();
    }

    /**
     * Handle the InventoryLeftover "updated" event.
     */
    public function updated(InventoryLeftover $inventoryLeftover): void
    {
            $inventoryLeftover->area = $this->detectArea();
    }

    /**
     * @return string
     */
    private function detectArea(): string
    {

        if (app()->runningInConsole()) {
            return 'cli';
        }

        $request = request();

        if ($request->is('api/*')) {
            return 'api';
        }

        return 'web';
    }
}
