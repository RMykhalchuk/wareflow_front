<?php

namespace App\Tables\TransportRequest;

use App\Models\Entities\Document\Document;
use App\Tables\Table\TableFilter;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = [];
        $containers = Document::whereHas('documentType', function ($q) {
            $q->where('key', 'zapyt_na_transport');
        });

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $containers);
    }
}
