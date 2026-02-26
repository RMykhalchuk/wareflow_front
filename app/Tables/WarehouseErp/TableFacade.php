<?php

namespace App\Tables\WarehouseErp;

use App\Models\Entities\WarehouseComponents\WarehouseErp;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = [];
        $warehouses = WarehouseErp::with($relationFields);


        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $warehouses);
    }
}
