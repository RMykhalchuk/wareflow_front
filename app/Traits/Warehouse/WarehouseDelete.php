<?php

namespace App\Traits\Warehouse;

use Illuminate\Support\Facades\DB;

trait WarehouseDelete
{
    public function hasLeftovers(): bool
    {
        return DB::table('leftovers')
            ->join('cells', 'leftovers.cell_id', '=', 'cells.id')
            // приєднуємо ряди
            ->leftJoin('rows', function ($join) {
                $join->on('cells.model_id', '=', 'rows.id')
                    ->where('cells.parent_type', '=', 'row');
            })
            // приєднуємо сектори
            ->leftJoin('sectors', function ($join) {
                $join->on('rows.sector_id', '=', 'sectors.id')
                    ->orOn(function ($on) {
                        $on->on('cells.model_id', '=', 'sectors.id')
                            ->where('cells.parent_type', '=', 'sector');
                    });
            })
            // приєднуємо зони
            ->leftJoin('warehouse_zones', function ($join) {
                $join->on('sectors.zone_id', '=', 'warehouse_zones.id')
                    ->orOn(function ($on) {
                        $on->on('cells.model_id', '=', 'warehouse_zones.id')
                            ->where('cells.parent_type', '=', 'zone');
                    });
            })
            ->whereNull('leftovers.deleted_at')
            ->where('warehouse_zones.warehouse_id', $this->id)
            ->exists();
    }
}
