<?php

namespace App\Tables\DocumentTypeInDocumentTable;

use App\Models\Entities\Document\Document;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = ['documentType','status'];
        $documents = Document::with($relationFields)->where('type_id', $_GET['type_id'])
            ->select();
        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $documents);
    }
}
