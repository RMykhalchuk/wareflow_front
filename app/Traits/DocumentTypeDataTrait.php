<?php

namespace App\Traits;

use App\Models\User;

trait DocumentTypeDataTrait
{
    public function scopeOnlyOrCurrentCompany($query)
    {
        return $query->where(function ($q) {
            $q->where('creator_company_id', User::currentCompany());
        });
    }
}
