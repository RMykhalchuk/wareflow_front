<?php

namespace App\Tables\LeftoverByGoods;

use App\Models\Entities\Leftover\Leftover;
use App\Tables\Leftover\TableSort;
use App\Tables\Table\TableFilter;


final class TableFacade
{
    public static function getFilteredData($warehouseId, $goodsId)
    {
        $relationFields = ['cell', 'package', 'goods.category', 'goods.barcodes','warehouse', 'container'];

        $leftovers = Leftover::with($relationFields)
            ->where('warehouse_id', $warehouseId)
            ->where('goods_id', $goodsId)
            ->where('quantity', '>', 0);


        // Передаємо колекцію далі в форматер
        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $leftovers);
    }

}
