<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait TransportDataTrait
{
    public function scopeAddFullName($q)
    {
        return $q->leftJoin('transport_models', 'transports.model_id', '=', 'transport_models.id')
            ->leftJoin('transport_brands', 'transports.brand_id', '=', 'transport_brands.id')
            ->addSelect(DB::raw("CONCAT(transport_brands.name, ' ', transport_models.name) AS name"));
    }
}
