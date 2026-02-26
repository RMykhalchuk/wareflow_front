<?php

namespace App\Traits;

use App\Models\Entities\Company\Company;
use App\Scopes\CompanyScope;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CompanySeparation
{
    public static function bootCompanySeparation()
    {
        static::addGlobalScope(new CompanyScope());

        static::creating(function ($model) {
            if (!empty($model->creator_company_id)) {
                return;
            }

            if (empty(app(AuthContextService::class)->getCompanyId())) {
                throw new \Exception('company_id must be set in user before saving the model.');
            }

            $model->creator_company_id = app(AuthContextService::class)->getCompanyId();
        });
    }

    public function scopeWithoutCreatorCompany($query)
    {
        return $query->withoutGlobalScope(CompanyScope::class);
    }

    public function creatorCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
