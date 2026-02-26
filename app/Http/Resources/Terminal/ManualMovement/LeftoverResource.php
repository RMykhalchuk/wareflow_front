<?php

namespace App\Http\Resources\Terminal\ManualMovement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeftoverResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $mainUnitsPerPackage = $this->package?->main_units_number ?? 1;

        return [
            'id' => $this->id,
            'goods' => [
                'id' => $this->goods_id,
                'name' => $this->goods->name,
                'barcode' => $this->goods->barcodes->pluck('barcode')->toArray(),
            ],
            'quantity' => $this->quantity,
            'total_quantity' => round($this->quantity * $mainUnitsPerPackage, 3),
            'measurement_unit' => $this->goods->measurement_unit->name,
            'package' => [
                'id' => $this->package_id,
                'name' => $this->package?->name,
                'quantity' => $mainUnitsPerPackage,
                'barcode' => $this->package?->barcodeString,
            ],
            'batch' => $this->batch,
            'manufacture_date' => $this->manufacture_date,
            'best_before_date' => $this->bb_date,
        ];
    }
}
