<?php

namespace App\Tables\Inventory;

use App\Tables\Table\AbstractTableSort;

/**
 * TableSort.
 */
final class TableSort extends AbstractTableSort
{
    /**
     * @param $model
     * @return mixed
     */
    #[\Override]
    public function getSortedData($model): mixed
    {
        $sortable = ['id', 'type', 'status', 'start_date', 'end_date', 'created_at'];

        if (!isset($_GET['sortdatafield']) || ($_GET['sortorder'] ?? '') === '') {
            return $model->orderBy('created_at', 'desc');
        }

        $sf = $_GET['sortdatafield'];
        $so = strtolower($_GET['sortorder']) === 'asc' ? 'asc' : 'desc';

        if (!in_array($sf, $sortable, true)) {
            $field = 'created_at';
            $order = 'desc';
        } else {
            $field = $this->formatTableData->renameFields($sf);
            $order = $so;
        }

        return $model->orderBy($field, $order);
    }
}
