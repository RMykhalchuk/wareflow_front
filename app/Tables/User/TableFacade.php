<?php

namespace App\Tables\User;

use App\Helpers\PostgreHelper;
use App\Models\User;
use App\Services\Web\Auth\AuthContextService;
use App\Tables\Table\TableFilter;
use Illuminate\Support\Facades\DB;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['usersInWorkspace'];

        $concat = PostgreHelper::dbConcat('physical_companies.first_name','physical_companies.surname');

        $companyId = app(AuthContextService::class)->getCompanyId();

        $lastWorking = DB::table('user_working_data')
            ->select('user_working_data.*', DB::raw('ROW_NUMBER() OVER (PARTITION BY user_working_data.user_id ORDER BY user_working_data.id DESC) as rn'))
            ->where('user_working_data.creator_company_id', $companyId);

        $users = User::with($relationFields)
            ->sameCompany()
            ->leftJoinSub($lastWorking, 'last_working_data', function ($join) {
                $join->on('last_working_data.user_id', '=', 'users.id')
                    ->where('last_working_data.rn', '=', 1);
            })
            ->leftJoin('_d_positions', 'last_working_data.position_id', '=', '_d_positions.id')
            ->leftJoin('companies', 'last_working_data.company_id', '=', 'companies.id')
            ->leftJoin(
                DB::raw("(SELECT companies.id, CASE WHEN companies.company_type_id = 1
            THEN {$concat}
            ELSE legal_companies.name END as company_name
          FROM companies
          LEFT JOIN physical_companies ON companies.company_id = physical_companies.id
          LEFT JOIN legal_companies ON companies.company_id = legal_companies.id) as company_with_details"),
                'last_working_data.company_id',
                '=',
                'company_with_details.id'
            )
            ->select(
                'last_working_data.*',
                'users.*',
                '_d_positions.name AS position_name',
                'companies.id as company_id',
            );


        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $users);
    }
}
