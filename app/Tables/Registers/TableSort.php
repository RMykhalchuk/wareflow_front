<?php

namespace App\Tables\Registers;

use App\Tables\Table\AbstractTableSort;

final class TableSort extends AbstractTableSort
{
    #[\Override]
    public function getSortedData($model)
    {
        if (isset($_GET['sortdatafield']) && $_GET['sortorder'] != '') {
            $sortDataField = $this->formatTableData->renameFields($_GET['sortdatafield']);
            $sortOrder = $_GET['sortorder'];

            if ($sortDataField == 'storekeeper') {
                $model = $model->leftJoin('users', 'users.id', '=', 'registers.storekeeper_id')
                    ->orderBy('users.surname', $sortOrder)
                    ->select('users.*');
            } elseif ($sortDataField == 'manager') {
                $model = $model->leftJoin('users', 'users.id', '=', 'registers.manager_id')
                    ->orderBy('users.surname', $sortOrder)
                    ->select('users.*');
            } else {
                $model = $model->orderBy($sortDataField, $sortOrder);
            }
        }
        return $model;
    }
}
