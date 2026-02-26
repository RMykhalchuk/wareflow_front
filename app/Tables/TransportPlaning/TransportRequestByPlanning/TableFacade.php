<?php

namespace App\Tables\TransportPlaning\TransportRequestByPlanning;

use App\Models\Entities\Document\Document;
use App\Tables\Table\TableFilter;
use App\Tables\TransportRequest\FormatTableData;
use App\Tables\TransportRequest\TableSort;

final class TableFacade
{
    public static function getFilteredData($id)
    {
        $relationFields = ['documentType','transport_plannings'];

        $goodInvoices = Document::with($relationFields)
            ->whereHas('documentType', function ($q) {
                $q->where('key', 'zapyt_na_transport');
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
