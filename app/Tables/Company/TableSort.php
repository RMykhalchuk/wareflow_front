<?php

namespace App\Tables\Company;

use App\Tables\Table\AbstractTableSort;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($sortDataField == 'name') {
                if ($this->existsInFilter($sortDataField)) {
                    $model = $model
                        ->orderBy('company_name', $sortOrder);
                } else {
                    $model = $model
                        ->leftJoin(DB::raw("(SELECT companies.id, CASE WHEN companies.company_type_id = 1 THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname) ELSE legal_companies.name END as name FROM companies LEFT JOIN physical_companies ON companies.company_id = physical_companies.id LEFT JOIN legal_companies ON companies.company_id = legal_companies.id) as company_with_details"), 'companies.id', '=', 'company_with_details.id')
                        ->select(['companies.*', DB::raw('company_with_details.name as company_name')])
                        ->orderBy('company_name', $sortOrder);
                }

            } elseif ($sortDataField == 'type') {
                $model = $model
                    ->leftJoin('company_types', 'companies.company_type_id', '=', 'company_types.id')
                    ->select(['companies.*', DB::raw('company_types.name as type_name')])
                    ->orderBy('type_name', $sortOrder);

            } elseif ($sortDataField == 'address') {
                $model = $model
                    ->orderBy(DB::raw("CONCAT(`settlements`.`name`, ' ', `streets`.`name`, ' ', `address_details`.`building_number`)"), $sortOrder);
            } elseif ($_GET['sortdatafield'] == 'taxNumber') {
                $model = $model->orderBy($sortDataField, $sortOrder);
            } elseif ($_GET['sortdatafield'] == 'property') {
                $model = $model
                    ->select(['companies.*', DB::raw('CASE WHEN companies.creator_id = ' . Auth::id() . " THEN 'Моя компанія' ELSE 'Контрагент' END as property")])
                    ->orderBy('property', $sortOrder);
            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }

        return $model;
    }
}
