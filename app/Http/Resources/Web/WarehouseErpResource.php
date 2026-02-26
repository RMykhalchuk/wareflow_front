<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseErpResource extends JsonResource
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
            "id_erp" => $this->id_erp,
            "creator_company_id" => $this->creator_company_id,
            "updated_at" => $this->updated_at,
            "created_at" => $this->created_at
        ];
    }
}
