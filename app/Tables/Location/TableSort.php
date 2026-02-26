<?php

namespace App\Tables\Location;

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
                        ->select(['locations.*', DB::raw('company_with_details.name as company_name')])
                        ->orderBy('company_name', $sortOrder);
                } else {
                    $model = $model
                        ->leftJoin(DB::raw("(SELECT companies.id, CASE WHEN companies.company_type_id = 1
                        THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname)
                        ELSE legal_companies.name END as name FROM companies
                        LEFT JOIN physical_companies ON companies.company_id = physical_companies.id
                        LEFT JOIN legal_companies ON companies.company_id = legal_companies.id) as company_with_details"),
                                   'locations.company_id', '=', 'company_with_details.id')
                        ->select(['locations.*', DB::raw('company_with_details.name as company_name')])
                        ->orderBy('company_name', $sortOrder);
                }

            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }

}
