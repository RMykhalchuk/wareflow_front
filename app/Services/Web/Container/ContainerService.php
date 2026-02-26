<?php

namespace App\Services\Web\Container;

use Illuminate\Support\Facades\DB;

/**
 * ContainerService.
 */
class ContainerService implements ContainerServiceInterface
{
    /**
     * @param string $cellId
     * @return array
     */
    public function getWithLeftoversByCell(string $cellId): array
    {
        $rows = DB::table('container_registers as cr')
            ->whereExists(function ($sq) use ($cellId) {
                $sq->select(DB::raw(1))
                    ->from('leftovers as l')
                    ->whereNull('l.deleted_at')
                    ->where('l.cell_id', $cellId)
                    ->whereColumn('l.container_id', 'cr.id');
            })
            ->select('cr.*')
            ->selectSub(function ($q) use ($cellId) {
                $q->from('leftovers as l')
                    ->whereNull('l.deleted_at')
                    ->where('l.cell_id', $cellId)
                    ->whereColumn('l.container_id', 'cr.id')
                    ->selectRaw('COUNT(1)');
            }, 'leftovers_count')
            ->orderByDesc('cr.created_at')
            ->get();

        return [
            'total' => $rows->count(),
            'data'  => $rows->values()->all(),
        ];
    }
}
