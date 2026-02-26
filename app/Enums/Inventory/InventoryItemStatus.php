<?php

namespace App\Enums\Inventory;

/**
 * InventoryItemStatus.
 */
enum InventoryItemStatus: int
{
    case BEFORE_INVENTORY = 1;
    case INVENTORIED      = 2;

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::BEFORE_INVENTORY => 'до інвентаризації',
            self::INVENTORIED      => 'інвентаризовано',
        };
    }
}
