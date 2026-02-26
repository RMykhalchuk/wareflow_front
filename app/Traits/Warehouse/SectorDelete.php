<?php

namespace App\Traits\Warehouse;

use Illuminate\Support\Facades\DB;

trait SectorDelete
{
    public function hasLeftovers(): bool
    {
        return DB::table('leftovers')
            ->join('cells', 'leftovers.cell_id', '=', 'cells.id')
            ->leftJoin('rows', function ($join) {
                $join->on('cells.model_id', '=', 'rows.id')
                    ->where('cells.parent_type', '=', 'row');
            })
            ->where(function ($query) {
                $query->where(function ($q) {
                    // клітинка прямо в секторі
                    $q->where('cells.parent_type', 'sector')
                        ->where('cells.model_id', $this->id);
                })
                    ->orWhere(function ($q) {
                        // клітинка через ряд, який належить сектору
                        $q->where('cells.parent_type', 'row')
                            ->where('rows.sector_id', $this->id);
                    });
            })
            ->exists();
    }
}
