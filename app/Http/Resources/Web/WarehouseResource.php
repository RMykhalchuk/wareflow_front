<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "local_id" => $this->local_id,
            "name" => $this->name,
            "type_id" => $this->type_id,
            "location_id" => $this->location_id,
            "warehouse_erp_id" => $this->warehouse_erp_id,
            "creator_company_id" => $this->creator_company_id,
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at
        ];
    }
}
