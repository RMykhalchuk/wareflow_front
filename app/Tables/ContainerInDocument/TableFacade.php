<?php

namespace App\Tables\ContainerInDocument;

use App\Models\Container\ContainerByDocument;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData()
    {
        $containers = ContainerByDocument::where('document_id', $_GET['document_id'])->select();

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter([], $containers);
    }
}
