<?php

namespace App\Tables\TransportRequest;

use App\Tables\Table\AbstractTableSort;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($_GET['sortdatafield'] != 'goods') {
                $model = $model->orderBy($sortDataField, $sortOrder);

            }
        }
        return $model;
    }
}
