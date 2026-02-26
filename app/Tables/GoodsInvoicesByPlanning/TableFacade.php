<?php

namespace App\Tables\GoodsInvoicesByPlanning;

use App\Models\Entities\Document\Document;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData($id)
    {
        $relationFields = ['goods.packages' => function ($q): void {
            $q->where('packages.type_id', 2);
        }];
        $goodInvoices = Document::with($relationFields)
            ->whereHas('documentType', function ($q) {
                $q->where('key', 'tovarna_nakladna');
            })
            ->whereHas('transport_plannings', function ($q) use ($id) {
                $q->where('transport_plannings.id', $id);
            });

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $goodInvoices);
    }
}
