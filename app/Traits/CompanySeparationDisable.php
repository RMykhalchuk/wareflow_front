<?php

namespace App\Traits;

use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Auth;

trait CompanySeparationDisable
{
    protected static bool $disableCompanySeparation = false;

    public static function disableCompanySeparation(): void
    {
        static::$disableCompanySeparation = true;
    }

    public static function enableCompanySeparationDisable(): void
    {
        static::$disableCompanySeparation = false;
    }

    public static function bootCompanySeparation()
    {
        static::addGlobalScope(new CompanyScope());

        static::creating(function ($model) {
            if (static::$disableCompanySeparation) {
                return;
            }

            if (!empty($model->creator_company_id)) {
                return;
            }

            $user = Auth::user();
            if (empty($user?->workingData->company_id)) {
                throw new \Exception('company_id must be set in user before saving the model.');
            }

            $model->creator_company_id = $user->workingData->company_id;
        });
    }

    public function scopeWithoutCreatorCompany($query)
    {
        return $query->withoutGlobalScope(CompanyScope::class);
    }
}
