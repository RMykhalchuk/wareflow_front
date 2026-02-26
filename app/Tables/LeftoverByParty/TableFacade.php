<?php

namespace App\Tables\LeftoverByParty;

use App\Models\Entities\Leftover\Leftover;
use App\Tables\Table\TableFilter;
use Illuminate\Support\Facades\DB;

final class TableFacade
{
    public static function getFilteredData($warehouses_ids)
    {
        $relationFields = [];
        $leftovers = Leftover::with($relationFields)
            ->where('quantity', '>', 0)
            ->leftJoin('goods', 'leftovers.goods_id', '=', 'goods.id')
            ->leftJoin('warehouses', 'leftovers.warehouse_id', '=', 'warehouses.id')
            ->select([
                DB::raw('goods_id as id'),
                DB::raw('SUM(count) as count'),
                DB::raw('goods.name as sku'),
                DB::raw('leftovers.consignment as party'),
                DB::raw('warehouses.id as warehouse_id'),
                DB::raw('warehouses.name as warehouse_name')
            ])
            ->groupBy('leftovers.goods_id', 'leftovers.consignment', 'leftovers.warehouse_id');

        if (!is_null($warehouses_ids)) {
            $leftovers->whereIn('warehouse_id', $warehouses_ids);
        }
        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $leftovers);
    }
}
