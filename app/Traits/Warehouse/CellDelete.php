<?php

namespace App\Traits\Warehouse;

use Illuminate\Support\Facades\DB;

trait CellDelete
{
    public function hasLeftovers()
    {
        return DB::table('leftovers')
            ->where('cell_id', $this->id)
            ->exists();
    }
}
