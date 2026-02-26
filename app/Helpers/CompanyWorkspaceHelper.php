<?php

namespace App\Helpers;

use App\Models\User;
use App\Services\Web\Auth\AuthContextService;
use phpDocumentor\Reflection\Types\Integer;

class CompanyWorkspaceHelper
{
    public static function setGlobalCompany(string $userId): void
    {
        $workingData = User::where('users.id', $userId)
            ->leftJoin('user_working_data', 'user_working_data.workspace_id', '=', 'users.current_workspace_id')
            ->select('user_working_data.company_id')->first();

        $companyId = $workingData?->company_id;
        app(AuthContextService::class)->setCompanyId($companyId);
    }
}
