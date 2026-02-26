<?php

namespace App\Services\Web\Warehouse\Cell\Strategy;

interface CellGenerationInterface {
    function generate(string $rowName, string $rowId,int $racks,int $floors,int $columns, array $cellData): array;
}
