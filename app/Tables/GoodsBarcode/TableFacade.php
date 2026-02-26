<?php

namespace App\Tables\GoodsBarcode;

use App\Models\Entities\Barcode;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData($id)
    {
        $relationFields = [];

        $packages = Barcode::with($relationFields)->where('goods_id', $id);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $packages);
    }
}
