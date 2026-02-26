<?php

namespace App\Tables\SkuInDocument;

use App\Models\Entities\Goods\GoodsByDocument;
use App\Tables\Table\TableFilter;
use App\Tables\Table\TableSort;

final class TableFacade
{
    public static function getFilteredData()
    {

        $documents = GoodsByDocument::with('goods')
            ->leftJoin('goods', 'goods_id', '=', 'goods.id')
            ->where('document_id', $_GET['document_id'])
            ->select('sku_by_documents.*', 'goods.name');

        $formatTable = new FormatTableData();
        $tableSort = new TableSort($formatTable);
        $filter = new TableFilter($tableSort, $formatTable);

        return $filter->filter([], $documents);
    }
}
