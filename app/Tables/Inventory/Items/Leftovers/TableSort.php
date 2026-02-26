<?php

namespace App\Tables\Inventory\Items\Leftovers;

use App\Tables\Table\AbstractTableSort;

/**
 * TableSort.
 */
final class TableSort extends AbstractTableSort
{
    /**
     * @param $model
     * @return mixed
     */
    #[\Override]
    public function getSortedData($model): mixed
    {
        return $model;
    }
}
