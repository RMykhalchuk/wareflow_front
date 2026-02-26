<?php

namespace App\Services\Terminal\Inventory;

/**
 * Inventory Manual Service Interface.
 */
interface InventoryManualServiceInterface
{
    /**
     * @return array
     */
    public function getList(): array;

    /**
     * @param string $cellId
     * @return array
     */
    public function getLeftoversByCell(string $cellId): array;

    /**
     * @param string $groupId
     * @return array
     */
    public function getLeftoversByGroup(string $groupId): array;
}
