<?php

namespace App\Tables\Leftover;

use App\Models\Entities\Leftover\Leftover;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData(array $warehouseIdArray, $cellId = null)
    {
        $relationFields = ['cell', 'package', 'goods.category', 'goods.barcodes', 'goods.manufacturerCompany', 'goods.providerCompany', 'warehouse', 'container'];


        if ($cellId) {
            $leftovers = Leftover::with($relationFields)->where('cell_id', $cellId)->where('quantity', '>', 0)->orderByDesc('created_at');
        } else {
            $leftovers = Leftover::with($relationFields)
                ->when($warehouseIdArray, function ($query, $ids) {
                    $query->whereIn('warehouse_id', $ids);
                })->where('quantity', '>', 0)->orderByDesc('created_at');
        }

        // Передаємо колекцію далі в форматер
        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $leftovers);
    }

}
