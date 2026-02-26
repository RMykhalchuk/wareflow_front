<?php

namespace App\Tables\TaskItem;

use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Tables\Leftover\TableSort;
use App\Tables\Table\TableFilter;


final class TableFacade
{
    public static function getFilteredData($document, $goodsId)
    {
        $relationFields = ['package', 'goods.category', 'goods.barcodes', 'container', 'user' => function ($query) {
            $query->select('id', 'name','surname','patronymic');
        }];

        $leftovers = IncomeDocumentLeftover::with($relationFields)
            ->where('document_id', $document->id)
            ->where('goods_id', $goodsId);


        // Передаємо колекцію далі в форматер
        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter($relationFields, $leftovers);
    }

}
