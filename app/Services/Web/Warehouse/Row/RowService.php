<?php

namespace App\Services\Web\Warehouse\Row;

use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\RowCellInfo;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Services\Web\Warehouse\Cell\Strategy\CellGenerationFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RowService implements RowServiceInterface
{
    public function create(Sector $sector, array $data): Row
    {
        $cellData = Arr::pull($data, 'cell');
        $count = (int) ($data['count'] ?? 1);
        unset($data['count']);

        if ($count < 1) {
            $count = 1;
        }

        $lastRowNumber = (int) $sector->rows()
            ->max(DB::raw("CAST(SUBSTRING(name FROM '[0-9]+') AS INTEGER)"));

        $createdRow = null;

        for ($i = 1; $i <= $count; $i++) {
            $rowData = $data;

            if (empty($rowData['name'])) {
                $newNumber = str_pad($lastRowNumber + $i, 2, '0', STR_PAD_LEFT);
                $rowData['name'] = $sector->code . $newNumber;
            }

            $rowData['cell_props'] = $cellData;

            $row = $sector->rows()->create($rowData);

            $cellGenerator = CellGenerationFactory::make('linear');
            $generated = $cellGenerator->generate(
                $rowData['name'],
                $row->id,
                $rowData['racks'],
                $rowData['floors'],
                $rowData['cell_count'],
                $cellData
            );

            Cell::insert($generated['cells']);
            RowCellInfo::insert($generated['rowCellInfos']);

            $createdRow = $row;
        }

        return $createdRow;
    }

    public function update(Sector $sector, Row $row, array $data): Row
    {
        if ($row->sector_id !== $sector->id) {
            abort(404, 'Row not found in this sector');
        }

        $cellsData = $data['cell'];
        $data['cell_props'] = $cellsData;

        unset($data['cell']);

        $row->update($data);

        $row->cells->each(function ($cell) use ($cellsData) {
            $cell->rowInfo()->update($cellsData);
        });

        return $row;
    }

    public function delete(Sector $sector, Row $row): void
    {
        if ($row->sector_id !== $sector->id) {
            abort(404, 'Row not found in this sector');
        }

        $row->delete();
    }
}
