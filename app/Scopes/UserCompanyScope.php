<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UserCompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $companyId = Auth::user()?->cached_company_id;

        if (!$companyId) {
            throw new \Exception('company_id must be set in user before querying the model.');
        }

        $builder->whereHas('workingData', function ($q) use ($companyId) {
            $q->where('company_id', $companyId);
        });
    }
}
