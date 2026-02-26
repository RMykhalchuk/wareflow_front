<?php

namespace App\Services\Web\Warehouse\Cell;

use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use Illuminate\Support\Collection;

interface CellServiceInterface
{
    public function create(Row $row, array $data): Collection;

    public function update(Row $row, Cell $cell, array $data): Cell;

    public function delete(Row $row, Cell $cell): void;
}

