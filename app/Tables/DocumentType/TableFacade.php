<?php

namespace App\Tables\DocumentType;

use App\Models\Entities\Document\Document;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = [];
        $documents = Document::find($_GET['document_id'])->relatedDocuments()->select();

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $documents);
    }
}
