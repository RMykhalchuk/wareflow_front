<?php

namespace App\Services\Terminal\Inventory;

use App\Models\Entities\Inventory\InventoryLeftover;

/**
 * InventoryLeftoverServiceInterface.
 */
interface InventoryLeftoverServiceInterface
{
    /**
     * Create a leftover linked to an inventory item.
     * If $data['leftover_id'] is present -> SOURCE_EXISTING, else SOURCE_NEW.
     */
    public function createForItem(string $inventoryItemId, array $data): InventoryLeftover;

    /**
     * @param string $inventoryLeftoverId
     * @param float|int $quantity
     * @param string|null $packageId
     * @return array
     */
    public function correctCurrent(string $inventoryLeftoverId, float|int $quantity, ?string $packageId = null): array;

    /**
     * @param string $inventoryLeftoverId
     * @return array
     */
    public function findOne(string $inventoryLeftoverId): array;

    /**
     * @param string $inventoryLeftoverId
     * @return void
     */
    public function deleteOne(string $inventoryLeftoverId): void;

    /**
     * @param array $inventoryLeftovers
     * @return array
     */
    public function leftoversSync(array $inventoryLeftovers): array;

    /**
     * @param array $items
     * @param string $inventoryItemId
     * @return array
     */
    public function syncByInventoryItem(array $items, string $inventoryItemId): array;
}
