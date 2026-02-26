<?php

namespace App\Tables\Goods;

use App\Models\Entities\Goods\Goods;
use App\Tables\Table\TableFilter;
use Illuminate\Support\Collection;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['manufacturer_country', 'category', 'barcodes' => function($query) {
            $query->select('entity_id','entity_type', 'barcode');
        }];

        $goods = Goods::with($relationFields)->orderBy('local_id');

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        $result = $filter->filter($relationFields, $goods);

        if ($result instanceof Collection) {
            return $result->sortBy(fn($row) => (int) data_get($row, 'local_id'))->values();
        }

        return $result;
    }
}
