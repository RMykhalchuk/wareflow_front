<?php

namespace App\Tables\User;

use App\Helpers\PostgreHelper;
use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($users)
    {
        $usersArray = [];
        for ($i = 0; $i < count($users); $i++) {
            $usersArray[] = $users[$i]->toArray();

            $usersArray[$i]['position'] = array_key_exists('position_name', $usersArray[$i])
                ? $usersArray[$i]['position_name'] : null;

            $role = $users[$i]?->workingData?->role;

            $usersArray[$i]['role'] = isset($role[0]) ? $role[0]->title : null;
            $usersArray[$i]['company'] = $users[$i]->workingData?->company?->full_name;
            $usersArray[$i]['is_online'] = $users[$i]->isOnline();
        }

        return TableCollectionResource::make(array_values($usersArray))->setTotal($users->total());
    }

    /**
     * @return \Illuminate\Contracts\Database\Query\Expression|string
     */
    #[\Override]
    /**
     * @return \Illuminate\Contracts\Database\Query\Expression|string
     */
    public function renameFields($fieldName)
    {
        if ($fieldName == 'is_online') {
            $fieldName = 'last_seen';
        } elseif ($fieldName == 'full_name') {
            $fieldName = DB::raw(PostgreHelper::dbConcat('users.surname','users.name'));
        } elseif ($fieldName == 'position') {
            $fieldName = '_d_positions.name';
        } elseif ($fieldName == 'role') {
            $fieldName = '_d_roles.name';
        } elseif ($fieldName = 'company') {
            $fieldName = 'company_name';
        }

        return $fieldName;
    }

    #[\Override]
    /**
     * @return string
     *
     * @psalm-return 'CASE
    * WHEN companies.company_type_id = 1
    * THEN (SELECT CONCAT(physical_companies.first_name, ' ', physical_companies.surname)
     * FROM physical_companies WHERE physical_companies.id = companies.company_id)
     * WHEN companies.company_type_id = 2
     * THEN (SELECT legal_companies.name
     * FROM legal_companies
     * WHERE legal_companies.id = companies.company_id)
     * END'|'name'
     */
    public function relationsSelectByField($relationName): string
    {
        $select = 'name';
        if ($relationName == 'company') {
            $concat = PostgreHelper::dbConcat('physical_companies.first_name','physical_companies.surname');
            $select = "CASE
             WHEN companies.company_type_id = 1
             THEN (SELECT {$concat}
             FROM physical_companies WHERE physical_companies.id = companies.company_id)
             WHEN companies.company_type_id = 2
             THEN (SELECT legal_companies.name
             FROM legal_companies
             WHERE legal_companies.id = companies.company_id)
             END";
        }

        return $select;
    }
}
