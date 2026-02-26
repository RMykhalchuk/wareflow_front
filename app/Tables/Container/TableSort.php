<?php

namespace App\Tables\Container;

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
                $model = $model
                    ->leftJoin(DB::raw("(SELECT companies.id, CASE WHEN companies.company_type_id = 1 THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname) ELSE legal_companies.name END as name FROM companies LEFT JOIN physical_companies ON companies.company_id = physical_companies.id LEFT JOIN legal_companies ON companies.company_id = legal_companies.id) as company_with_details"), 'containers.company_id', '=', 'company_with_details.id')
                    ->orderBy('company_with_details.name', $sortOrder);

            } elseif ($sortDataField == 'type') {
                $model = $model
                    ->leftJoin('container_types', 'containers.type_id', '=', 'containers_types.id')
                    ->orderBy('container_types.name', $sortOrder);

            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }
}
