<?php

namespace App\Observers;


use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Leftover\Leftover;

class ContainerRegisterObserver
{
    /**
     * Handle the ContainerRegister "updated" event.
     * Update leftover.warehouse_id, leftover.cell_id when ContainerRegister change own cell_id
     */
    public function updated(ContainerRegister $container): void
    {
        if ($container->isDirty('cell_id') && !empty($container->cell_id)) {
            $cell = $container->cell;
            Leftover::query()
                ->where('container_id', $container->id)
                ->update(['cell_id' => $container->cell_id, "warehouse_id" => $cell->getWarehouseId()]);
        }
    }
}
