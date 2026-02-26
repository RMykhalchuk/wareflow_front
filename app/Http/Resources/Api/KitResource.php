<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'local_id' => $this->local_id,
            'name' => $this->name,
            'category' => $this->category,
            'is_batch_accounting' => $this->is_batch_accounting,
            'is_weight' => $this->is_weight,
            'weight_netto' => $this->weight_netto,
            'weight_brutto' => $this->weight_brutto,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
           // 'status_id' => $this->status_id,
            'measurement_unit_id' => $this->measurement_unit_id,
            'erp_id' => $this->erp_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'goods' => $this->goodsKitItems,
            'packages' => PackageResource::collection($this->whenLoaded('packages'))
        ];
    }
}
