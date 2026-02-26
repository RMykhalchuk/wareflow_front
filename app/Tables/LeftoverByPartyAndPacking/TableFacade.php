<?php

namespace App\Tables\LeftoverByPartyAndPacking;

use App\Models\Entities\Leftover\Leftover;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['goods', 'packages'];
        $leftovers = Leftover::with($relationFields)
            ->where('quantity', '>', 0)
            ->select(['*', 'goods.party as party', 'packages.name as package', 'goods.name as sku']);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $leftovers);
    }
}
