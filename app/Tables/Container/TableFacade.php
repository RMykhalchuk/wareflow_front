<?php

namespace App\Tables\Container;


use App\Models\Entities\Container\Container;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['company', 'type'];
        $containers = Container::with($relationFields);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $containers);
    }
}
