<?php

namespace App\Tables\Task;

use App\Models\Entities\Task\Task;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData($warehouseId)
    {
        $relationFields = ['type', 'document'];

        $tasks = Task::with($relationFields)->whereHas('document',function ($query) use ($warehouseId){
            $query->where('warehouse_id',$warehouseId);
        });

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $tasks);
    }
}
