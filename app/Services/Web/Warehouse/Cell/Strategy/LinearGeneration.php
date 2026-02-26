<?php

namespace App\Services\Web\Warehouse\Cell\Strategy;

use App\Enums\Containers\Cell\CellType;
use Illuminate\Support\Str;

class LinearGeneration implements CellGenerationInterface
{
    public function generate(string $rowName, string $rowId, int $racks, int $floors, int $columns, array $cellData): array
    {
        $cells = [];
        $rowCellInfos = [];
        $now = now();

        for ($i = 1; $i <= $racks; $i++) {
            for ($j = 1; $j <= $floors; $j++) {
                for ($k = 1; $k <= $columns; $k++) {
                    $cellId = (string)Str::uuid();

                    // базовий запис для cells
                    $cells[] = [
                        'id' => $cellId,
                        'status_id' => 1,
                        'code' => $rowName . '-' . str_pad($i, 2, '0', STR_PAD_LEFT)
                            . '-' . str_pad($j, 2, '0', STR_PAD_LEFT)
                            . '-' . str_pad($k, 2, '0', STR_PAD_LEFT),
                        'parent_type' => 'row',
                        'model_id' => $rowId
                    ];

                    // технічні характеристики
                    $rowCellInfos[] = [
                        'id' => (string)Str::uuid(),
                        'cell_id' => $cellId,
                        'rack' => $i,
                        'floor' => $j,
                        'column' => $k,
                        'height' => $cellData['height'],
                        'width' => $cellData['width'],
                        'deep' => $cellData['deep'],
                        'max_weight' => $cellData['max_weight']
                    ];
                }
            }
        }

        return ['cells' => $cells, 'rowCellInfos' => $rowCellInfos];
    }
}


