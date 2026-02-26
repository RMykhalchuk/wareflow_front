<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseZoneResource extends JsonResource
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

            'has_temp' => $this->has_temp,
            'temp_from' => $this->temp_from,
            'temp_to' => $this->temp_to,

            'has_humidity' => $this->has_humidity,
            'humidity_from' => $this->humidity_from,
            'humidity_to' => $this->humidity_to,

            'warehouse_id' => $this->warehouse_id,
            'creator_company_id' => $this->creator_company_id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
