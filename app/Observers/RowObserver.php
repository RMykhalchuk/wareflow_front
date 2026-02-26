<?php

namespace App\Observers;

use App\Models\Entities\WarehouseComponents\Row;

class RowObserver
{
    public function deleted(Row $row): void
    {
        $row->cells()->update(['deleted_at' => now()]);
    }

    public function restored(Row $row): void
    {
        $row->cells()->withTrashed()->update(['deleted_at' => null]);
    }
}