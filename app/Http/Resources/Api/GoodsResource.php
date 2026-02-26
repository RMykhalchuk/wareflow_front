<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'local_id' => $this->local_id,
            'name' => $this->name,

            'is_batch_accounting' => $this->is_batch_accounting,
            'is_weight' => $this->is_weight,

            'weight_netto' => $this->weight_netto,
            'weight_brutto' => $this->weight_brutto,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,

            'temp_from' => $this->temp_from,
            'temp_to' => $this->temp_to,
            'humidity_from' => $this->humidity_from,
            'humidity_to' => $this->humidity_to,
            'dustiness_from' => $this->dustiness_from,
            'dustiness_to' => $this->dustiness_to,

            'status_id' => $this->status_id,
            'measurement_unit_id' => $this->measurement_unit_id,
            'adr_id' => $this->adr_id,
            'manufacturer_country_id' => $this->manufacturer_country_id,
            'category' => $this->category,

            'expiration_date' => $this->expiration_date,
            'erp_id' => $this->erp_id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            //
            // ─── COMPANIES ───────────────────────────────────────────────────────────────
            //

            'brand' =>  [
                'id' => $this->providerCompany->id ?? null,
                'name' => $this->providerCompany->full_name ?? null,
            ],

            'manufacturer' => [
                'id' => $this->brandCompany->id ?? null,
                'name' => $this->brandCompany->full_name ?? null,
            ],

            'provider' => [
                'id' => $this->manufacturerCompany->id ?? null,
                'name' => $this->manufacturerCompany->full_name ?? null,
            ],

            //
            // ─── PACKAGES ────────────────────────────────────────────────────────────────
            //

            'packages' => PackageResource::collection($this->whenLoaded('packages')),
        ];
    }
}
