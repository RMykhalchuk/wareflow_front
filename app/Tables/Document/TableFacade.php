<?php

namespace App\Tables\Document;

use App\Models\Entities\Document\Document;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData()
    {
        $relationFields = [
            'documentType',
            'status',
            'warehouse' => function ($query) {
                $query->select('id', 'name');
            },
        ];
        $documents = Document::with($relationFields)->where('type_id', $_GET['document_id'])->orderBy('local_id', 'desc');

        if (isset($_GET['warehouse_id'])) {
            $documents = $documents->where('warehouse_id', $_GET['warehouse_id']);
        }

        $documents = $documents->select();
        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $documents);
    }
}
