<?php

namespace App\Tables\Inventory\Items;

use App\Models\Entities\Inventory\InventoryItem;
use App\Tables\Table\TableFilter;

/**
 * TableFacade.
 */
final class TableFacade
{
    /**
     * @param string $inventoryId
     * @return array
     */
    public static function getFilteredData(string $inventoryId): array
    {
        $relationFields = [];

        $query = InventoryItem::query()
            ->where('inventory_id', $inventoryId);

        $formatTable = new FormatTableData();
        $tableSort   = new TableSort($formatTable);

        return new TableFilter($tableSort, $formatTable)->filter($relationFields, $query);
    }

    public static function getFilteredDataByZone(string $inventoryId): array
    {
        $relationFields = [];

        $query = InventoryItem::query()
            ->where('inventory_id', $inventoryId);

        $formatTable = new ZoneFormatTableData($inventoryId);
        $tableSort   = new TableSort($formatTable);

        return (new TableFilter($tableSort, $formatTable))
            ->filter($relationFields, $query);
    }
}
