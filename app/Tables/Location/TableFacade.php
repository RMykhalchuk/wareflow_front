<?php

namespace App\Tables\Location;

use App\Models\Entities\Location;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['country', 'settlement', 'company'];
        $locations = Location::with($relationFields)
            ->select('locations.*',);


        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $locations);
    }
}
