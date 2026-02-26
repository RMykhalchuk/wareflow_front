<?php

namespace App\Services\Web\Company;

use App\Models\Entities\User\UserWorkingData;
use App\Services\Web\Auth\AuthContextService;
use Illuminate\Support\Facades\Auth;

final class CompanyContextService
{
    public static function apply(): void
    {
        if (Auth::check()) {
            $user = Auth::user();

            $companyId = app(AuthContextService::class)->getCompanyId();

            if (!isset($companyId)) {
                $workingData = $user->workingData()->withoutCreatorCompany()->first();
                $companyId = $workingData?->company_id;

                app(AuthContextService::class)->setCompanyId($companyId);
            }

            if (!isset($companyId)) {
                $workingData = UserWorkingData::withoutCreatorCompany()->where('user_id',$user->id)->first();
                $companyId = $workingData?->creator_company_id;
                app(AuthContextService::class)->setCompanyId($companyId);
            }
        }
    }
}

