<?php

namespace App\Services\Terminal\Inventory;

use App\Models\Entities\Inventory\Inventory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Inventory service.
 */
interface InventoryServiceInterface
{
    /**
     * @param array $data
     * @return array
     */
    public function store(array $data): array;

    /**
     * @param Inventory $inventory
     * @param array $data
     * @return Inventory
     */
    public function update(Inventory $inventory, array $data): Inventory;

    /**
     * @param Inventory $inventory
     * @return void
     */
    public function delete(Inventory $inventory): void;

    /**
     * @param string $id
     * @param array $with
     * @return Inventory
     */
    public function get(string $id, array $with = []): Inventory;

    /**
     * @param array $filters
     * @param int $perPage
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], int $perPage = 15, ?int $page = null): LengthAwarePaginator;

    /**
     * @param Request $request
     * @return array
     */
    public function gridTableDataFromRequest(Request $request): array;

    /**
     * @param string $id
     * @param string $action
     * @return array
     */
    public function transition(string $id, string $action): array;

    /**
     * @param Request $request
     * @return array
     */
    public function indexDataFromRequest(Request $request): array;

    /**
     * @param array $filters
     * @param int|null $limit
     * @return Collection
     */
    public function getAll(array $filters = [], ?int $limit = null): Collection;

    /**
     * @param string $inventoryItemId
     * @return array
     */
    public function getContainersWithLeftoversByItem(string $inventoryItemId): array;
}
