<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'local_id' => $this->local_id,
            'parent_id' => $this->parent_id,
            'type_id' => $this->type_id,
            'name' => $this->name,
            'main_units_number' => $this->main_units_number,
            'package_count' => $this->package_count,
            'weight_netto' => $this->weight_netto,
            'weight_brutto' => $this->weight_brutto,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
            'child_number' => $this->child_number,
            'child_id' => $this->child_id,
            'barcodeString' => $this->barcodeString,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
