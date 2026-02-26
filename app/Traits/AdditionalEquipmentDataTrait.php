<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait AdditionalEquipmentDataTrait
{
    public function scopeAddFullName($q)
    {
        return $q->leftJoin('additional_equipment_models', 'additional_equipment.model_id', '=', 'additional_equipment_models.id')
            ->leftJoin('additional_equipment_brands', 'additional_equipment.brand_id', '=', 'additional_equipment_brands.id')
            ->addSelect(DB::raw("CONCAT(additional_equipment_brands.name, ' ', additional_equipment_models.name) AS name"));
    }
}
