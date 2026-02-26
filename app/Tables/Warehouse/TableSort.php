<?php

namespace App\Tables\Warehouse;

use App\Tables\Table\AbstractTableSort;
use Illuminate\Support\Facades\DB;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($sortDataField == 'company') {
                if ($this->existsInFilter($sortDataField)) {
                    $model = $model
                        ->select(['warehouses.*', DB::raw('company_with_details.name as company_name')])
                        ->orderBy('company_name', $sortOrder);
                } else {
                    $model = $model
                        ->leftJoin(DB::raw("(SELECT companies.id, CASE WHEN companies.company_type_id = 1 THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname) ELSE legal_companies.name END as name FROM companies LEFT JOIN physical_companies ON companies.company_id = physical_companies.id LEFT JOIN legal_companies ON companies.company_id = legal_companies.id) as company_with_details"), 'warehouses.company_id', '=', 'company_with_details.id')
                        ->select(['warehouses.*', DB::raw('company_with_details.name as company_name')])
                        ->orderBy('company_name', $sortOrder);
                }

            } elseif ($sortDataField == 'type') {
                if ($this->existsInFilter($sortDataField)) {
                    $model = $model
                        ->select(['warehouses.*', DB::raw('warehouse_types.name as type_name')])
                        ->orderBy('type_name', $sortOrder);
                } else {
                    $model = $model
                        ->leftJoin('warehouse_types', 'warehouses.type_id', '=', 'warehouse_types.id')
                        ->select(['warehouses.*', DB::raw('warehouse_types.name as type_name')])
                        ->orderBy('type_name', $sortOrder);
                }

            } elseif ($sortDataField == 'location') {
                $model = $model
                    ->orderBy(DB::raw("CONCAT(_d_settlement_name, ' ', _d_street_name, ' ', address_details.building_number)"), $sortOrder);
            } elseif ($sortDataField == 'contact') {
                if ($this->existsInFilter($sortDataField)) {
                    $model = $model
                        ->select(['warehouses.*', DB::raw("CONCAT(users.surname,' ',users.name) as full_name")])
                        ->orderBy('full_name', $sortOrder);
                } else {
                    $model = $model
                        ->leftJoin('users', 'warehouses.user_id', '=', 'users.id')
                        ->select(['warehouses.*', DB::raw("CONCAT(users.surname,' ',users.name) as full_name")])
                        ->orderBy('full_name', $sortOrder);
                }

            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }

}
