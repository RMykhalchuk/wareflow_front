<?php

namespace App\Traits;

use App\Models\Entities\System\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait UserDataTrait
{
    public function scopeAddFullName($q)
    {
        return $q->addSelect(DB::raw("CONCAT(users.name, ' ', users.surname) AS full_name"));
    }

    public function scopeFilterByWorkspace($q)
    {
        return $q->leftJoin('user_working_data', 'users.id', '=', 'user_working_data.user_id')
            ->where('user_working_data.workspace_id', Workspace::current());
    }


    public function scopeWithWorkingData($q)
    {
        return $q->leftJoin('user_working_data as u', 'users.id', '=', 'u.user_id')
            ->where('u.creator_company_id', '=', User::currentCompany())
            ->addSelect(
                'users.*',
                'u.company_id',
                'u.position_id',
                'u.driving_license_number',
                'u.health_book_number',
                'u.driving_license_doctype',
                'u.health_book_doctype',
                'u.driver_license_date',
                'u.health_book_date',
                'u.user_id',
                'u.workspace_id',
                'u.creator_company_id',
                'u.id as user_working_data_id',
                'u.creator_company_id'
            )
            ->distinct();
    }
}
