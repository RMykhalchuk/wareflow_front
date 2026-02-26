<?php

namespace App\Http\Resources\Terminal\Leftovers;

use App\Models\Entities\Task\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeftoverByContainerResource extends JsonResource
{
    public function __construct(protected $container, protected $leftovers)
    {
        parent::__construct(null);
    }

    public function toArray($request = null): array
    {
        $leftovers = $this->leftovers;

        $containerLeftovers = $leftovers
            ->groupBy('cell_id')
            ->map(fn($group) => $this->transformData($group))
            ->values()
            ->toArray();

        return [
            'cell_code' => $this->container->cell->code,
            'zone_name' => $this->container->cell->allocation['zone'],
            'container_code' => $this->container->code,
            'leftovers' => $containerLeftovers,
        ];
    }

    private function getPackage($cellLeftover): array
    {
        return [
            'name' => $cellLeftover->package->name,
            'quantity' => $cellLeftover->quantity,
        ];
    }

    private function transformData($cellLeftovers, $type = 'cell'): array
    {
        $leftoverArray = [];

        foreach ($cellLeftovers as $key => $leftoverByCell) {
            $unitName = $leftoverByCell->goods->measurement_unit->name;
            $quantityInMain = $leftoverByCell->quantity * $leftoverByCell->package->main_units_number;

            $leftoverArray [$key] = [
                "container_code" => $leftoverByCell->container?->code,
                "status" => $leftoverByCell->status,
                "condition" => $leftoverByCell->has_condition,
                "barcode" => $leftoverByCell->package?->barcode?->barcode,
                "batch" => $leftoverByCell->batch,
                "manufacture_date" => $leftoverByCell->manufacture_date,
                "bb_date" => $leftoverByCell->bb_date,
                "measurement_unit" => $unitName,
                "total_quantity" => $quantityInMain,
                "packing_info" => $this->getPackage($leftoverByCell),
            ];

            if ($type == 'container') {
                $leftoverArray[$key]["container_id"] = $leftoverByCell->container_id;
            }

        }


        return array_values($leftoverArray);
    }
}

