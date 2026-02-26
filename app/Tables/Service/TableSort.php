<?php

namespace App\Tables\Service;

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

            if ($sortDataField == 'category') {
                $model = $model
                    ->leftJoin('service_categories', 'services.category_id', '=', 'service_categories.id')
                    ->select(['services.*', DB::raw('service_categories.name as category_name')])
                    ->orderBy('category_name', $sortOrder);
            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }
}
