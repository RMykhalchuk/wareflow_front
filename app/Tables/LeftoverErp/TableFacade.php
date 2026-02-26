<?php

namespace App\Tables\LeftoverErp;

use App\Models\Entities\LeftoverErp\LeftoverErp;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['goods', 'warehouseErp'];

        $leftovers = LeftoverErp::with([
                'goods.category',
                'goods.barcodes',
                'warehouseErp',
            ]);

        $formatTable = new FormatTableData();
        $tableSort   = new TableSort($formatTable);
        $filter      = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $leftovers);
    }
}
