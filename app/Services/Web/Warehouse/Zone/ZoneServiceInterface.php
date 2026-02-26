<?php

namespace App\Services\Web\Warehouse\Zone;

use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseZone;

interface ZoneServiceInterface
{
    public function create(Warehouse $warehouse, array $data): WarehouseZone;

    public function update(Warehouse $warehouse, WarehouseZone $zone, array $data): WarehouseZone;

    public function delete(Warehouse $warehouse, WarehouseZone $zone): void;
}
