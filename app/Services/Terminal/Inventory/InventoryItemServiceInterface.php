<?php

namespace App\Services\Terminal\Inventory;

use App\Models\Entities\Inventory\Inventory;
use App\Models\Entities\Inventory\InventoryItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Inventory Item Service Interface.
 */
interface InventoryItemServiceInterface
{
    /**
     * @param string $id
     * @return InventoryItem
     */
    public function get(string $id): InventoryItem;

    /**
     * @param string $inventoryId
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function listByInventory(string $inventoryId, int $perPage = 50, int $page = 1): LengthAwarePaginator;

    /**
     * @param array $data
     * @return InventoryItem
     */
    public function store(array $data): InventoryItem;

    /**
     * @param array $data
     * @return InventoryItem
     */
    public function create(array $data): InventoryItem;

    /**
     * @param InventoryItem $item
     * @param array $data
     * @return InventoryItem
     */
    public function update(InventoryItem $item, array $data): InventoryItem;

    /**
     * @param InventoryItem $item
     * @return void
     */
    public function delete(InventoryItem $item): void;

    /**
     * @param string $inventoryId
     * @param Request $request
     * @return array
     */
    public function gridItemsDataFromRequest(string $inventoryId, Request $request): array;

    /**
     * @param string $inventoryId
     * @param Request $request
     * @return array
     */
    public function gridItemsDataByZone(string $inventoryId, Request $request): array;

    /**
     * @param Inventory $inventory
     * @param array $filters
     * @param bool $skipExisting
     * @return int
     */
    public function seedFromLeftovers(Inventory $inventory, array $filters = [], bool $skipExisting = true): int;

    /**
     * @param Inventory $inventory
     * @param bool $skipExisting
     * @return int
     */
    public function seedFromInventory(Inventory $inventory, bool $skipExisting = true): int;

    /**
     * @param string $id
     * @param float|int $quantity
     * @return array
     */
    public function correctQuantity(string $id, float|int $quantity): array;

    /**
     * @param Inventory $inventory
     * @return int
     */
    public function applyRealQtyToLeftoversByInventory(Inventory $inventory): int;

    /**
     * @param Inventory $inventory
     * @return void
     */
    public function recalculateContainerStatusesForInventory(Inventory $inventory): void;

    /**
     * @param string $inventory
     * @param string $inventory_item
     * @return mixed
     */
    public function showItemFromInventory(string $inventory, string $inventory_item): mixed;

    /**
     * @param string $inventoryItemId
     * @return array
     */
    public function reset(string $inventoryItemId): array;

    /**
     * @param object $r
     * @param array $dicts
     * @return array
     */
    public function transformLeftoverRowForItemView(object $r, array $dicts): array;

    /**
     * @param string $inventoryItemId
     * @param string $containerId
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getLeftoversByItemAndContainer(
        string $inventoryItemId,
        string $containerId,
        int $perPage = 50,
        int $page = 1
    ): array;
}
