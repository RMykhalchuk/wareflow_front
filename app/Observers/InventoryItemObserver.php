<?php

namespace App\Observers;

use App\Enums\Inventory\InventoryItemStatus;
use App\Models\Entities\Inventory\Inventory;
use App\Models\Entities\Inventory\InventoryItem;

/**
 * Inventory Item Observer.
 */
class InventoryItemObserver
{

    /**
     * @param InventoryItem $model
     * @return void
     */
    public function saving(InventoryItem $model): void
    {
        if (empty($model->area)) {
            $model->area = $this->detectArea();
        }
    }

    /**
     * @return string
     */
    private function detectArea(): string
    {
        if (app()->runningInConsole()) return 'cli';

        $req = request();

        if ($req && ($req->is('api/*') || $req->expectsJson())) return 'api';

        return 'web';
    }

    /**
     * Handle the InventoryItem "created" event.
     */
    public function created(InventoryItem $inventoryItem): void
    {
        //
    }

    /**
     * Handle the InventoryItem "updated" event.
     */
    public function updated(InventoryItem $inventoryItem): void
    {
        if (!$inventoryItem->wasChanged('status')) {
            return;
        }

        if ($inventoryItem->status !== InventoryItemStatus::INVENTORIED) {
            return;
        }

        $inventory = Inventory::find($inventoryItem->inventory_id);

        if (!$inventory) {
            return;
        }

        $currentStatus = (int) $inventory->status;

        // PENDING → IN_PROGRESS при першій проінвентаризованій комірці
        if ($currentStatus === Inventory::STATUS_PENDING) {
            $inventoriedCount = InventoryItem::where('inventory_id', $inventory->id)
                ->where('status', InventoryItemStatus::INVENTORIED->value)
                ->count();

            if ($inventoriedCount === 1) {
                $inventory->status     = Inventory::STATUS_IN_PROGRESS;
                $inventory->start_date = now();
                $inventory->saveQuietly();
                $currentStatus = Inventory::STATUS_IN_PROGRESS;
            }
        }

        // IN_PROGRESS → FINISHED_BEFORE коли всі комірки проінвентаризовано
        if ($currentStatus === Inventory::STATUS_IN_PROGRESS) {
            $totalCount = InventoryItem::where('inventory_id', $inventory->id)->count();

            $inventoriedCount = InventoryItem::where('inventory_id', $inventory->id)
                ->where('status', InventoryItemStatus::INVENTORIED->value)
                ->count();

            if ($totalCount > 0 && $totalCount === $inventoriedCount) {
                $inventory->status   = Inventory::STATUS_FINISHED_BEFORE;
                $inventory->end_date = now();
                $inventory->saveQuietly();
            }
        }
    }

    /**
     * Handle the InventoryItem "deleted" event.
     */
    public function deleted(InventoryItem $inventoryItem): void
    {
        //
    }

    /**
     * Handle the InventoryItem "restored" event.
     */
    public function restored(InventoryItem $inventoryItem): void
    {
        //
    }

    /**
     * Handle the InventoryItem "force deleted" event.
     */
    public function forceDeleted(InventoryItem $inventoryItem): void
    {
        //
    }
}
