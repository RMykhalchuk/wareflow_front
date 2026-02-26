<?php

namespace App\Tables\Inventory\Items\Leftovers;

use App\Models\Entities\Inventory\InventoryItem;
use App\Tables\Table\TableFilter;
use Illuminate\Support\Facades\DB;

/**
 * TableFacade.
 */
final class TableFacade
{
    /**
     * @param string $inventoryId
     * @return array
     */
    public static function getFilteredDataByInventory(string $inventoryId): array
    {
        $relationFields = [];

        $query = InventoryItem::query()
            ->where('inventory_id', $inventoryId)
            ->where('status', 2)
            ->whereExists(function ($sq) {
                $sq->select(DB::raw(1))
                    ->from('inventory_leftovers as il')
                    ->whereColumn('il.inventory_item_id', 'inventory_items.id');
            })
            ->orderByDesc('created_at');

        $formatTable = new FormatTableData();
        $tableSort   = new TableSort($formatTable);

        return new TableFilter($tableSort, $formatTable)->filter($relationFields, $query);
    }

    /**
     * @param string $inventoryItemId
     * @return array
     */
    public static function getFilteredDataByItem(string $inventoryItemId): array
    {
        $relationFields = [];

        $query = InventoryItem::query()
            ->join('inventory_leftovers as il', 'il.inventory_item_id', '=', 'inventory_items.id')
            ->where('inventory_items.id', $inventoryItemId)
            ->orderBy('il.created_at', 'desc')
            ->select([
                'il.id as il_id',
                'il.leftover_id',
                'il.goods_id',
                'il.package_id',
                'il.area',
                'il.quantity',
                'il.current_leftovers',
                'il.batch',
                'il.manufacture_date',
                'il.bb_date',
                'il.source_type',
                'il.container_registers_id',
                'inventory_items.id as item_id',
                'inventory_items.cell_id',
                'inventory_items.qty as item_qty',
                'inventory_items.real_qty as item_real_qty',
                'inventory_items.updated_at as item_updated_at',
                'inventory_items.update_id as item_update_id',
                'inventory_items.creator_id as item_creator_id',
                'inventory_items.status as item_status',
                'il.condition',
                'il.updated_at as leftover_updated_at',
            ]);

        $formatTable = new FormatTableDataByItem($inventoryItemId);
        $tableSort   = new TableSort($formatTable);

        return new TableFilter($tableSort, $formatTable)->filter($relationFields, $query);
    }
}
