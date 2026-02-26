<?php

namespace App\Services\Web\Inventory;

use App\Services\Terminal\Inventory\InventoryManualServiceInterface;
use App\Services\Terminal\Leftovers\LeftoverServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Inventory Manual Service.
 */
class InventoryManualService implements InventoryManualServiceInterface
{
    /**
     * @param LeftoverServiceInterface $leftoverService
     */
    public function __construct(
        private readonly LeftoverServiceInterface $leftoverService,
    ) {}

    /**
     * @return array
     */
    public function getList(): array
    {
        $pageSize = (int) request('pagesize', 10);
        $pageNum  = (int) request('pagenum', 0);
        $offset   = $pageNum * $pageSize;

        $baseQB = DB::table('inventory_manual_leftover_logs as imll')
            ->join('leftovers', 'leftovers.id', '=', 'imll.leftover_id')
            ->join('cells', 'cells.id', '=', 'leftovers.cell_id')
            ->whereNull('leftovers.deleted_at')
            ->where('leftovers.quantity', '>', 0)
            ->where('imll.executor_id', \Auth::user()->id)
            ->whereNotNull('imll.group_id');

        $total = (clone $baseQB)
            ->distinct('imll.group_id')
            ->count('imll.group_id');

        $rows = (clone $baseQB)
            ->select([
                'imll.group_id as group_id',
                DB::raw('MAX(imll.id) as max_imll_id'),
                DB::raw('MAX(imll.created_at) as inv_ts'),
                DB::raw('MIN(cells.code) as cell_code'),
                DB::raw('COUNT(imll.id) as logs_count'),
            ])
            ->groupBy('imll.group_id')
            ->orderByDesc(DB::raw('MAX(imll.created_at)'))
            ->offset($offset)
            ->limit($pageSize)
            ->get();

        $data = $rows->map(function ($r, $index) use ($offset, $total) {
            $ts = $r->inv_ts ? \Carbon\Carbon::parse($r->inv_ts) : null;

            return [
                'id'         => $total - ($offset + $index),
                'local_id'   => (string) $r->group_id,
                'cell'       => (string) ($r->cell_code ?? '-'),
                'logs_count' => (int) $r->logs_count,
                'invented'   => [
                    'name' => \Auth::user()?->initial(),
                    'date' => $ts ? $ts->format('Y.m.d') : '-',
                    'time' => $ts ? $ts->format('H:i')    : '-',
                ],
            ];
        });

        return [
            'total' => $total,
            'data'  => $data,
        ];
    }

    /**
     * @param string $cellId
     * @return array
     */
    public function getLeftoversByCell(string $cellId): array
    {
        $res = $this->leftoverService->findLoggedLeftoversByCell($cellId);

        return $res['leftovers'] ?? [];
    }

    /**
     * @param string $groupId
     * @return array
     */
    public function getLeftoversByGroup(string $groupId): array
    {
        $res = $this->leftoverService->findLoggedLeftoversByGroup($groupId);

        return $res['leftovers'] ?? [];
    }
}
