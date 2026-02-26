<?php

namespace App\Scopes;

use App\Models\Entities\User\UserWorkingData;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $companyId = app(AuthContextService::class)->getCompanyId();

        if (!$companyId) {
            $workingData = UserWorkingData::withoutCreatorCompany()->where('user_id',\Auth::user()->id)->first();
            $companyId = $workingData?->creator_company_id;
            //throw new \Exception('company_id must be set in user before querying the model.');
        }

        $builder->where($model->getTable() . '.creator_company_id', $companyId);
    }
}
