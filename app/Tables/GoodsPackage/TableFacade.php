<?php

namespace App\Tables\GoodsPackage;

use App\Models\Entities\Package;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData($id)
    {
        $relationFields = ['type'];

        $packages = Package::with($relationFields)->where('goods_id', $id);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $packages);
    }
}
