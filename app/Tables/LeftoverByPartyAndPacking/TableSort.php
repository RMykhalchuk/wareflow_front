<?php

namespace App\Tables\LeftoverByPartyAndPacking;

use App\Tables\Table\AbstractTableSort;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($sortDataField == 'name') {
                $model = $model->orderBy('goods.name', $sortOrder);
            } elseif ($sortDataField == 'party') {
                $model = $model->orderBy('goods.party', $sortOrder);
            } elseif ($sortDataField == 'package') {
                $model = $model->orderBy('packages.name', $sortOrder);
            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }
}
