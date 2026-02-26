<?php

namespace App\Tables\Warehouse;

use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['type', 'location', 'erpWarehouses'];
        $warehouses = Warehouse::with($relationFields)
            ->select(
                'warehouses.*'
            )->orderBy('local_id');


        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $warehouses);
    }
}
