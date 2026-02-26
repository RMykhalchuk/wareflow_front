<?php

namespace App\Traits\Warehouse;

use Illuminate\Support\Facades\DB;

trait ZoneDelete
{
    public function hasLeftovers(): bool
    {
        return DB::table('leftovers')
            ->join('cells', 'leftovers.cell_id', '=', 'cells.id')
            // Клітинки, які належать рядам
            ->leftJoin('rows', function ($join) {
                $join->on('cells.model_id', '=', 'rows.id')
                    ->where('cells.parent_type', '=', 'row');
            })
            // Сектори через ряд
            ->leftJoin('sectors', 'rows.sector_id', '=', 'sectors.id')
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Клітинка прямо в зоні
                    $q->where('cells.parent_type', 'zone')
                        ->where('cells.model_id', $this->id); // або cells.parent_id = $this->id
                })
                    ->orWhere(function ($q) {
                        // Клітинка в ряді -> сектор -> зона
                        $q->where('cells.parent_type', 'row')
                            ->where('sectors.zone_id', $this->id);
                    });
            })
            ->exists();
    }


}
