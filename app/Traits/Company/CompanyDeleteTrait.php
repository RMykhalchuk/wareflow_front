<?php

namespace App\Traits\Company;

use App\Models\Entities\Goods\Goods;

trait CompanyDeleteTrait
{
    public function hasRelatedGoods(): bool
    {
        return Goods::where(function ($q) {
            $q->where('creator_company_id', $this->id)
                ->orWhere('manufacturer', $this->id)
                ->orWhere('brand', $this->id)
                ->orWhere('provider', $this->id);
        })
            ->whereNull('deleted_at')
            ->exists();
    }
}
