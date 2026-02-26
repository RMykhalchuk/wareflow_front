<?php

namespace App\Http\Resources\Terminal\Leftovers;


use Illuminate\Http\Resources\Json\JsonResource;

class LeftoverByProductResource extends JsonResource
{
    protected $goods;
    protected $leftovers;

    public function __construct($goods, $leftovers)
    {
        parent::__construct(null);
        $this->goods = $goods;
        $this->leftovers = $leftovers;
    }

    public function toArray($request = null): array
    {
        [$totalQuantity, $totalAvailable] = $this->calculateTotals();

        $cells = $this->leftovers->groupBy('cell_id')->map(function ($cellLeftovers) {
            return $this->transformCell($cellLeftovers);
        })->values()->toArray();

        return [
            'name' => $this->goods->name,
            'barcodes' => $this->goods->barcodes->pluck('barcode')->toArray(),
            'manufacturer' => $this->goods->manufacturer,
            'measurement_unit' => $this->goods->measurement_unit->name,
            'total_quantity' => $totalQuantity,
            'total_available' => $totalAvailable,
            'cells' => $cells,
        ];
    }

    private function calculateTotals(): array
    {
        $totalQuantity = 0;
        $totalAvailable = 0;

        foreach ($this->leftovers as $leftover) {
            $totalQuantity += $leftover->quantity;

            if ($leftover->has_condition) {
                $totalAvailable += $leftover->quantity;
            }
        }

        return [$totalQuantity, $totalAvailable];
    }

    private function transformCell($cellLeftovers): array
    {
        $totalCellQuantity = 0;
        $totalCellAvailable = 0;
        $unconditionQuantityArray = 0;
        $packageArray = [];

        foreach ($cellLeftovers as $leftoverByCell) {
            $quantityInMain = $leftoverByCell->quantity * $leftoverByCell->package->main_units_number;

            $totalCellQuantity += $quantityInMain;

            if ($leftoverByCell->has_condition) {
                $totalCellAvailable += $quantityInMain;
            } else {
                $unconditionQuantityArray += $quantityInMain;
            }


            $packageArray[] = [
                'name' => $leftoverByCell->package->name,
                'quantity' => $leftoverByCell->quantity,
            ];
        }

        $first = $cellLeftovers->first();

        return [
            "zone_name" => $first->cell->allocation['zone'],
            "cell" => ['id' => $first->cell->id, 'code' => $first->cell->code],
            "uncondition_quantity" => $unconditionQuantityArray,
            "measurement_unit" => $first->goods->measurement_unit->name,
            "total_quantity" => $totalCellQuantity,
            "total_quantity_available" => $totalCellAvailable,
            "packing_info" => $packageArray,
        ];
    }
}
