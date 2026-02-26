<?php

namespace App\Services\Web\Warehouse\Row;

use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;


interface RowServiceInterface
{
    public function create(Sector $sector, array $data): Row;

    public function update(Sector $sector, Row $row, array $data): Row;

    public function delete(Sector $sector, Row $row): void;
}

