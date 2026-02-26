<?php

namespace App\Tables\DocumentTask;

use App\Models\Entities\Task\Task;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;
use App\Tables\Task\FormatTableData;

final class TableFacade
{
    public static function getFilteredData(string $documentId)
    {
        $relationFields = ['type', 'document'];

        $tasks = Task::with($relationFields)->where('document_id',$documentId);

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);
        return $filter->filter($relationFields, $tasks);
    }
}
