<?php

namespace App\Tables\ContainerRegister;


use App\Models\Entities\Container\ContainerRegister;
use App\Tables\Table\TableFilter;


final class TableFacade
{
    public static function getFilteredData()
    {
        $relations = ['cell','container'];
        $containers = ContainerRegister::with($relations)->select();

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter([], $containers);
    }
}
