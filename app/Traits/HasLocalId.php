<?php

namespace App\Traits;

use App\Services\Web\Auth\AuthContextService;

trait HasLocalId
{
    public static function bootHasLocalId()
    {
        static::creating(function ($model) {
            // Пропускаємо, якщо local_id явно задано
            if (!empty($model->local_id)) {
                return;
            }

            $companyId = app(AuthContextService::class)->getCompanyId();
            if ($companyId){
                $max = static::where('creator_company_id', $companyId)->max('local_id');
                $model->local_id = $max ? $max + 1 : 1;
            }else {
                $model->local_id = 1;
            }

        });
    }
}
