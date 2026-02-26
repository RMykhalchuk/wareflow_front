<?php

namespace App\Http\Resources\Terminal\Leftovers;

use App\Models\Entities\Task\Task;
use Illuminate\Http\Resources\Json\JsonResource;

class LeftoverByCellResource extends JsonResource
{
    protected $cell;
    protected $leftovers;

    public function __construct($cell, $leftovers)
    {
        parent::__construct(null);
        $this->cell = $cell;
        $this->leftovers = $leftovers;
    }

    public function toArray($request = null): array
    {
        $loadedWeight = $this->calculateWeight();

        $leftovers = $this->leftovers->groupBy('cell_id')->map(function ($cellLeftovers) {
            return $this->transformCell($cellLeftovers);
        })->values()->toArray();

        $hasTask = Task::where('cell_id', $this->cell->id)->exists();

        return [
            'cell_code' => $this->cell->code,
            'loaded_weight' => $loadedWeight,
            'max_weight' => $this->cell->max_weight,
            'has_task' => $hasTask,
            'leftovers' => $leftovers,
        ];
    }

    private function calculateWeight(): int
    {
        $totalWeight = 0;

        foreach ($this->leftovers as $leftover) {
            $totalWeight += $leftover->quantity * $leftover->package->weight_brutto;
        }

        return $totalWeight;
    }

    private function transformCell($cellLeftovers): array
    {
        $totalCellQuantity = 0;
        $totalCellAvailable = 0;
        $unconditionQuantityArray = 0;

        // Групуємо по ID пакування
        $groupedByPackage = $cellLeftovers->groupBy('package_id');

        $packageArray = $groupedByPackage->map(function ($items) {
            return [
                'name' => $items->first()->package->name,
                'quantity' => $items->sum('quantity'), // сумуємо кількість
            ];
        })->values()->toArray();

        // Рахуємо загальні кількості
        foreach ($cellLeftovers as $leftoverByCell) {
            $quantityInMain = $leftoverByCell->quantity * $leftoverByCell->package->main_units_number;
            $totalCellQuantity += $quantityInMain;

            if ($leftoverByCell->has_condition) {
                $totalCellAvailable += $quantityInMain;
            } else {
                $unconditionQuantityArray += $quantityInMain;
            }
        }

        $first = $cellLeftovers->first();

        return [
            "zone_name" => $first->cell->allocation['zone'],
            "cell" => [
                'id' => $first->cell->id,
                'code' => $first->cell->code
            ],
            'product' => [
                'id' => $first->goods->id,
                'name' => $first->goods->name
            ],
            "uncondition_quantity" => $unconditionQuantityArray,
            "measurement_unit" => $first->goods->measurement_unit->name,
            "total_quantity" => $totalCellQuantity,
            "total_quantity_available" => $totalCellAvailable,
            "packing_info" => $packageArray,
        ];
    }
}
