<?php

namespace App\Tables\ServiceInDocument;

use App\Models\ServiceByDocument;
use App\Tables\ContainerInDocument\FormatTableData;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData()
    {
        $services = ServiceByDocument::where('document_id', $_GET['document_id'])->select();

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter([], $services);
    }
}
