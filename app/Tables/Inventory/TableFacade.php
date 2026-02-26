<?php

namespace App\Tables\Inventory;

use App\Models\Entities\Inventory\Inventory;
use App\Tables\Table\TableFilter;

/**
 * TableFacade.
 */
final class TableFacade
{
    /**
     * @param string $creatorId
     * @return array
     */
    public static function getFilteredData(string $creatorId): array
    {
        $relationFields = ['performer', 'performers', 'creator'];

        $query = Inventory::withTrashed()->with($relationFields)
            ->where('creator_id', $creatorId);

        $formatTable = new FormatTableData();
        $tableSort   = new TableSort($formatTable);

        return new TableFilter($tableSort, $formatTable)->filter($relationFields, $query);
    }
}
