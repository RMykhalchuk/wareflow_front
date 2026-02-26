<?php

namespace App\Tables\GoodsPackage;

use App\Tables\Table\AbstractTableSort;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($_GET['sortdatafield'] == 'type') {
                $model = $model
                    ->leftJoin('package_types', 'packages.type_id', '=', 'package_types.id')
                    ->orderBy('package_types.name', $sortOrder);
            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }

        }
        return $model;
    }
}
