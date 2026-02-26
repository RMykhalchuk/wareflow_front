<?php

namespace App\Traits\Warehouse;

use Illuminate\Support\Facades\DB;

trait RowDelete
{
    public function hasLeftovers()
    {
        return DB::table('leftovers')
            ->join('cells', 'leftovers.cell_id', '=', 'cells.id')
            ->leftJoin('rows', function ($join) {
                $join->on('cells.model_id', '=', 'rows.id')
                    ->where('cells.parent_type', '=', 'row');
            })
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('cells.parent_type', 'row')
                        ->where('cells.model_id', $this->id);
                });
            })
            ->exists();
    }

}
