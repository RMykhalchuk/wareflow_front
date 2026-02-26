<?php

namespace App\Services\Web\Warehouse\Cell;

use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use App\Services\Web\Warehouse\Cell\Strategy\CellGenerationFactory;
use App\Services\Web\Warehouse\Cell\Strategy\CellGeneratorStrategy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CellService implements CellServiceInterface
{
    public function create(Row $row, array $data): Collection
    {
        $row->load('sector');
        $strategy = CellGenerationFactory::make('linear');

        $generator = new CellGeneratorStrategy($strategy);

        return Cell::insert($generator->generate(
            [
                'rowName' => $row->sector->name,
                'rowId' => $row->id,
                'racks' => $data['racks_number'],
                'floors' => $data['floors_number'],
                'columns' => $data['rack_cells_number'],
            ]));
    }

    public function update(Row $row, Cell $cell, array $data): Cell
    {
        if ($cell->model_id !== $row->id || $cell->parent_type !== 'row') {
            abort(404, 'Cell not found in this row');
        }

        DB::transaction(function () use ($cell, $data) {
            if (isset($data['type'])) {
                $cell->update(['type' => $data['type']]);
            }

            $cell->rowInfo->update(collect($data)->except('type')->toArray());
        });

        return $cell->fresh(['rowInfo']);
    }

    public function delete(Row $row, Cell $cell): void
    {
        if ($cell->model_id !== $row->id || $cell->parent_type !== 'row') {
            abort(404, 'Cell not found in this row');
        }

        DB::transaction(function () use ($cell) {
            $cell->delete();
        });
    }
}
