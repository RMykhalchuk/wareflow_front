<?php

namespace App\Observers;

use App\Models\Entities\WarehouseComponents\Sector;

class SectorObserver
{
    public function deleted(Sector $sector): void
    {
        $sector->cells()->update(['deleted_at' => now()]);
    }

    public function restored(Sector $sector): void
    {
        $sector->cells()->withTrashed()->update(['deleted_at' => null]);
    }
}