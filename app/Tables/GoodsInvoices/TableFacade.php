<?php

namespace App\Tables\GoodsInvoices;

use App\Models\Entities\Document\Document;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['goods.packages' => function ($q): void {
            $q->where('packages.type_id', 2);
        }];
        $containers = Document::with($relationFields)->whereHas('documentType', function ($q) {
            $q->where('key', 'tovarna_nakladna');
        });


        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $containers);
    }
}
