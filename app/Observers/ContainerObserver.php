<?php

namespace App\Observers;

use App\Models\Entities\Container\Container;
use Illuminate\Support\Facades\DB;

class ContainerObserver
{
    /**
     * Handle the Container "created" event.
     */
    public function created(Container $container): void
    {
        //
    }

    /**
     * Handle the Container "updated" event.
     */
    public function updated(Container $container): void
    {
        if ($container->wasChanged('code_format')) {
            $oldCodeFormat = $container->getOriginal('code_format');
            $newCodeFormat = $container->code_format;
            $prefixLength = strlen($oldCodeFormat);

            DB::table('container_registers')
                ->where('container_id', $container->id)
                ->update([
                    'code' => DB::raw("CONCAT('{$newCodeFormat}', SUBSTRING(code, " . ($prefixLength + 1) . "))")
                ]);
        }
    }

    /**
     * Handle the Container "deleted" event.
     */
    public function deleted(Container $container): void
    {
        //
    }

    /**
     * Handle the Container "restored" event.
     */
    public function restored(Container $container): void
    {
        //
    }

    /**
     * Handle the Container "force deleted" event.
     */
    public function forceDeleted(Container $container): void
    {
        //
    }
}
