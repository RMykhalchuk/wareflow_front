<?php

namespace App\Services\Web\Warehouse\Sector;

use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\WarehouseZone;

interface SectorServiceInterface
{
    public function create(WarehouseZone $zone, array $data): Sector;

    public function update(WarehouseZone $zone, Sector $sector, array $data): Sector;

    public function delete(WarehouseZone $zone, Sector $sector): void;
}

