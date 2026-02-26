<?php

namespace App\Tables\GoodsToContainerRegister;

use App\Models\Entities\Leftover\Leftover;
use App\Tables\Leftover\FormatTableData;
use App\Tables\Leftover\TableSort;
use App\Tables\Table\TableFilter;


final class TableFacade
{
    public static function getFilteredData($containerRegisterId)
    {
        $relationFields = ['cell', 'package', 'goods', 'warehouse'];

        $leftovers = Leftover::with($relationFields)->where('container_id', $containerRegisterId);

        $formatTable = new FormatTableData();

        // Use classes from Leftovers
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $leftovers);
    }

}
