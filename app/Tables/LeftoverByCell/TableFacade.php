<?php

namespace App\Tables\LeftoverByCell;

use App\Models\Entities\Leftover\Leftover;
use App\Tables\LeftoverByPartyAndPacking\FormatTableData;
use App\Tables\LeftoverByPartyAndPacking\TableSort;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData($cellId)
    {
        $relationFields = ['goods', 'packages'];
        $leftovers = Leftover::with($relationFields)->where('cell_id', $cellId)->where('quantity', '>', 0)
            ->select(['*', 'goods.party as party', 'packages.name as package', 'goods.name as sku']);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $leftovers);
    }
}
