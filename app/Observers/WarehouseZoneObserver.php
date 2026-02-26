<?php

namespace App\Observers;

use App\Models\Entities\WarehouseComponents\WarehouseZone;
use Illuminate\Support\Facades\DB;

class WarehouseZoneObserver
{
    /**
     * Handle the WarehouseZone "created" event.
     */
    public function created(WarehouseZone $warehouseZone): void
    {
        //
    }

    /**
     * Handle the WarehouseZone "updated" event.
     */
    public function updated(WarehouseZone $warehouseZone): void
    {
        if ($warehouseZone->wasChanged('name')) {
            $newName = addslashes($warehouseZone->name);

            DB::table('cells')
                ->where('parent_type', 'zone')
                ->where('model_id', $warehouseZone->id)
                ->where('code', 'like', '%-%')
                ->update([
                    'code' => DB::raw("
                regexp_replace(
                    code,
                    '^.*-',
                    '{$newName}-'
                )
            ")
                ]);
        }
    }

    /**
     * Handle the WarehouseZone "deleted" event.
     */
    public function deleted(WarehouseZone $warehouseZone): void
    {
        $warehouseZone->cells()->update(['deleted_at' => now()]);
    }

    /**
     * Handle the WarehouseZone "restored" event.
     */
    public function restored(WarehouseZone $warehouseZone): void
    {
        $warehouseZone->cells()->withTrashed()->update(['deleted_at' => null]);
    }

    /**
     * Handle the WarehouseZone "force deleted" event.
     */
    public function forceDeleted(WarehouseZone $warehouseZone): void
    {
        //
    }
}
