<?php

namespace App\Services\Terminal\Leftovers;

use App\Enums\Cells\CellStatus;
use App\Models\Entities\Container\Container;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\WarehouseComponents\Cell;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


/**
 *
 */
class LeftoverService implements LeftoverServiceInterface
{

    /**
     * @param string $query
     * @return Collection
     */
    public function findLeftoverByProduct(string $query): Collection
    {
        return Goods::with(['barcodes'])
            ->whereHas('leftovers')
            ->where(function ($q) use ($query) {
                $q->where('name', 'ILIKE', "%{$query}%")
                    ->orWhereHas('barcodes', function ($q) use ($query) {
                        $q->where('barcode', 'ILIKE', "%{$query}%");
                    });
            })
            ->get()
            ->map(fn($goods) => [
                'id' => $goods->id,
                'name' => $goods->name,
                'barcode' => $goods->barcodes->first()->barcode ?? null,
            ]);
    }

    /**
     * @param string $query
     * @return Collection
     */
    public function findLeftoverByCell(string $query): Collection
    {
        return Cell::where('code', 'ILIKE', "%{$query}%")
            ->whereNull('deleted_at')
            ->inCurrentWarehouse()
            ->withCount('leftovers')
            ->get(['id', 'code'])
            ->map(fn($cell) => [
                'id' => $cell->id,
                'code' => $cell->code,
                'etetete' => $cell->code,
                'quantity' => $cell->leftovers_count
            ]);
    }

    /**
     * @param string $query
     * @return Collection
     */
    public function findLeftoverByContainer(string $query): Collection
    {
        return ContainerRegister::inCurrentWarehouse()
            ->where('code', 'ILIKE', "%{$query}%")
            ->whereHas('cell', function ($q) {
                $q->inCurrentWarehouse();
            })
            ->withCount([
                            'leftovers as quantity' => function ($q) {
                                $q->whereHas('cell', fn($c) => $c->inCurrentWarehouse());
                            }
                        ])
            ->get(['id', 'code'])
            ->map(fn($container) => [
                'id' => $container->id,
                'code' => $container->code,
                'quantity' => $container->quantity
            ]);
    }

    public function getLeftoverByContainer(string $containerId): array
    {
        $container = ContainerRegister::with(['cell'])->findOrFail($containerId);

        $leftovers = Leftover::with(
            [
                'container',
                'goods.barcodes',
                'goods.packages',
                'package',
                'goods.measurement_unit',
            ])
            ->where('container_id', $container->id)
            ->get();

        return [$container, $leftovers];
    }

    /**
     * @param Goods $goods
     * @return array
     */
    public function getLeftoverByProduct(Goods $goods): array
    {
        $goods->load(['barcodes', 'measurement_unit', 'packages']);

        $leftovers = Leftover::with(
            [
                'container',
                'goods.barcodes',
                'goods.packages',
                'package',
                'goods.measurement_unit',
                'cell'
            ])->where('goods_id', $goods->id)->get();

        return [$goods, $leftovers];
    }

    /**
     * @param string $cellId
     * @return array
     */
    public function findLeftoversByCell(string $cellId): array
    {
        $leftovers = $this->getLeftoversForCell($cellId);
        $cell      = $this->getCellForLeftoversOrId($leftovers, $cellId);

        $loadedWeight = $this->calculateLoadedWeight($leftovers);
        $statusMeta   = $this->getCellStatusMeta($cell, $cellId);

        $lastInvent = null;

        if ((int) ($statusMeta['status_code'] ?? 0) === CellStatus::BLOCKED->value) {
            $lastInvent = $this->getLastInventMeta($cellId);
        }

        return [
            'cell_id'       => $cellId,
            'cell_code'     => $cell->code ?? null,
            'loaded_weight' => $loadedWeight,
            'has_task'      => (bool) ($cell->has_task ?? false),

            'status_code'             => $statusMeta['status_code'],
            'status_label'            => $statusMeta['status_label'],
            'blocked_by_inventory_id' => $statusMeta['blocked_by_inventory_id'],

            'last_invent' => $lastInvent,

            'leftovers' => $this->transformLeftovers($leftovers),
        ];
    }

    /**
     * @param string $cellId
     * @return Collection
     */
    private function getLeftoversForCell(string $cellId): Collection
    {
        return Leftover::query()
            ->with([
                       'goods',
                       'goods.barcodes',
                       'goods.packages',
                       'goods.measurement_unit',
                       'package',
                       'cell',
                   ])
            ->where('cell_id', $cellId)
            ->get();
    }

    /**
     * @param Collection $leftovers
     * @param string $cellId
     * @return Cell|null
     */
    private function getCellForLeftoversOrId(Collection $leftovers, string $cellId): ?Cell
    {
        return optional($leftovers->first())->cell
            ?? Cell::select('id', 'code', 'status')->find($cellId);
    }

    /**
     * @param Collection $leftovers
     * @return float|int
     */
    private function calculateLoadedWeight(Collection $leftovers): float|int
    {
        $weight = 0;

        foreach ($leftovers as $leftover) {
            $weight += $leftover->package->weight_brutto * $leftover->quantity;
        }

        return $weight;
    }

    /**
     * @param string $cellId
     * @return array
     */
    private function getLastInventMeta(string $cellId): array
    {
        $lastFromInventoryLeftovers = DB::table('inventory_leftovers as il')
            ->join('inventory_items as ii', 'ii.id', '=', 'il.inventory_item_id')
            ->join('inventories as inv', 'inv.id', '=', 'ii.inventory_id')
            ->where('ii.cell_id', $cellId)
            ->where('inv.status', 4)
            ->max('il.updated_at');

        $lastFromManualLogs = DB::table('inventory_manual_leftover_logs as imll')
            ->join('leftovers as l', 'l.id', '=', 'imll.leftover_id')
            ->where('l.cell_id', $cellId)
            ->max('imll.created_at');

        $lastInventAt = collect([$lastFromInventoryLeftovers, $lastFromManualLogs])
            ->filter()
            ->max();

        return [
            'from_inventory_leftovers' => $lastFromInventoryLeftovers,
            'from_manual_logs'         => $lastFromManualLogs,
            'last_at'                  => $lastInventAt,
        ];
    }

    /**
     * @param Cell|null $cell
     * @param string $cellId
     * @return array
     */
    private function getCellStatusMeta(?Cell $cell, string $cellId): array
    {
        $statusCode            = null;
        $statusLabel           = null;
        $blockedByInventoryId  = null;

        if ($cell) {
            $statusRaw = $cell->status;

            if ($statusRaw instanceof CellStatus) {
                $statusCode  = $statusRaw->value;
                $statusLabel = $statusRaw->label();

                if ($statusRaw === CellStatus::BLOCKED) {
                    $blockedByInventoryId = $this->getLastInventoryIdForCell($cellId);
                }
            } else {
                $statusCode = $statusRaw;

                if (is_int($statusRaw) && isset(CellStatus::LABELS[$statusRaw])) {
                    $statusLabel = CellStatus::LABELS[$statusRaw];
                }

                if ((int) $statusRaw === CellStatus::BLOCKED->value) {
                    $blockedByInventoryId = $this->getLastInventoryIdForCell($cellId);
                }
            }
        }

        return [
            'status_code'            => $statusCode,
            'status_label'           => $statusLabel,
            'blocked_by_inventory_id'=> $blockedByInventoryId,
        ];
    }

    /**
     * @param string $cellId
     * @return string|null
     */
    private function getLastInventoryIdForCell(string $cellId): ?string
    {
        return DB::table('inventory_items')
            ->where('cell_id', $cellId)
            ->orderByDesc('updated_at')
            ->value('inventory_id');
    }

    /**
     * @param Collection $leftovers
     * @return array
     */
    private function transformLeftovers(Collection $leftovers): array
    {
        return $leftovers->map(function (Leftover $l) {
            $goods   = $l->goods;
            $barcode = $goods && $goods->relationLoaded('barcodes')
                ? optional($goods->barcodes->first())->barcode
                : null;

            return [
                'leftover_id' => $l->id,
                'goods_id'    => $l->goods_id,
                'goods_name'  => $goods->name ?? null,
                'barcode'     => $barcode,

                'qty'          => $l->quantity ?? null,
                'batch'        => $l->batch ?? null,
                'expires_at'   => $l->bb_date ?? null,
                'created_date' => $l->manufacture_date ?? null,

                'measurement_unit' => [
                    'code'       => optional($goods->measurement_unit)->code ?? null,
                    'name'       => optional($goods->measurement_unit)->name ?? null,
                    'short_name' => optional($goods->measurement_unit)->short_name ?? null,
                    'id'         => optional($goods->measurement_unit)->id ?? null,
                ],

                'package' => [
                    'id'         => optional($l->package)->id ?? null,
                    'name'       => optional($l->package)->name ?? null,
                    'units_qty'  => optional($l->package)->main_units_number ?? null,
                ],

                'goods_packages' => $goods && $goods->relationLoaded('packages')
                    ? $goods->packages->map(function ($p) {
                        return [
                            'id'         => $p->id ?? null,
                            'name'       => $p->name ?? null,
                            'short_name' => $p->short_name ?? null,
                            'ratio'      => $p->ratio ?? null,
                            'weight'     => $p->weight ?? null,
                            'volume'     => $p->volume ?? null,
                        ];
                    })->values()->all()
                    : [],
            ];
        })->values()->all();
    }

    //TODO - Переробити під нормальну структуру
    /**
     * @param string $cellId
     * @return array
     */
    public function findLoggedLeftoversByCell(string $cellId): array
    {
        $leftovers = Leftover::query()
            ->with([
                       'goods',
                       'goods.barcodes',
                       'goods.packages',
                       'goods.measurement_unit',
                       'goods.company',
                       'goods.category',
                       'goods.categories',
                       'package',
                       'cell',
                       'container',
                       'latestManualLog.executor',
                   ])
            ->where('cell_id', $cellId)
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('inventory_manual_leftover_logs as imll')
                    ->whereColumn('imll.leftover_id', 'leftovers.id');
            })
            ->get();

        $cell = optional($leftovers->first())->cell
            ?? Cell::select('id','code')->find($cellId);

        $loadedWeight = 0;

        foreach ($leftovers as $leftover) {
            $pkgWeight = optional($leftover->package)->weight_brutto ?? 0;
            $qty       = (float) ($leftover->quantity ?? 0);
            $loadedWeight += $pkgWeight * $qty;
        }

        return [
            'cell_id'       => $cellId,
            'cell_code'     => $cell->code ?? null,
            'loaded_weight' => $loadedWeight,
            'has_task'      => (bool) ($cell->has_task ?? false),

            'leftovers' => $leftovers->map(function ($l) {
                $goods = $l->goods;

                $log       = $l->latestManualLog;
                $qtyAfter  = $log?->quantity_after;
                $qtyBefore = $log?->quantity_before;

                $current   = $qtyAfter  !== null ? (float) $qtyAfter  : (float) ($l->quantity ?? 0);
                $erp       = $qtyBefore !== null ? (float) $qtyBefore : (float) ($l->quantity ?? 0);

                $diff = $current - $erp;
                $divergence = $diff == 0.0 ? '0' : (($diff > 0 ? '+' : '') . self::fmtNum($diff));

                $respAt   = $log?->created_at;
                $respExec = $log?->executor;
                $respName = $respExec?->name
                    ?? (isset($respExec->surname, $respExec->name)
                        ? trim($respExec->surname . ' ' . $respExec->name)
                        : null);

                $barcode = $goods && $goods->relationLoaded('barcodes')
                    ? optional($goods->barcodes->first())->barcode
                    : null;

                $manufacturerName = $goods?->getAttribute('manufacturer')
                    ?? optional($goods?->company)->name
                    ?? null;

                $brandName = $goods?->getAttribute('brand') ?? null;

                $categoryName = optional($goods->category)->name
                    ?? optional($goods->categories)->name
                    ?? null;

                $container = $l->relationLoaded('container') ? $l->container : null;

                return [
                    'id'        => (string) $l->local_id,
                    'local_id'  => (string) ($l->id ?? ''),

                    'goods_name' => $goods->name ?? null,
                    'barcode'    => $barcode,
                    'name'       => [
                        'manufacturer' => $manufacturerName,
                        'category'     => $categoryName,
                        'brand'        => $brandName,
                    ],
                    'party'         => $l->batch ?? null,
                    'manufactured'  => optional($l->manufacture_date)?->toDateString(),
                    'expires_at'    => optional($l->bb_date)?->toDateString(),
                    'package' => [
                        'name' => optional($l->package)->name ?? null,
                    ],
                    'condition'     => isset($l->has_condition) ? (string) $l->has_condition : null,
                    'container' => [
                        'code' => optional($container)->code ?? null,
                        'id'   => optional($container)->id ?? null,
                    ],
                    'qty'            => $current,
                    'leftovers_erp'  => $erp,
                    'divergence'     => $divergence,
                    'responsible_name' => $respName ?? '-',
                    'responsible_date' => $respAt ? $respAt->format('Y.m.d') : '-',
                    'responsible_time' => $respAt ? $respAt->format('H:i')    : '-',
                    'real' => true,
                ];
            })->values()->all(),
        ];
    }

    /**
     * @param string $cellId
     * @return array
     */
    public function getLeftoverByCell(string $cellId): array
    {
        $cell = Cell::inCurrentWarehouse()->findOrFail($cellId);

        $leftovers = Leftover::with(
            [
                'container',
                'goods.barcodes',
                'goods.packages',
                'package',
                'goods.measurement_unit',
            ])
            ->where('cell_id', $cellId)
            ->get();

        return [$cell, $leftovers];
    }

    /**
     * @param string $cellId
     * @param Goods $goods
     * @return array
     */
    public function getCellContents(string $cellId, Goods $goods): array
    {
        $cell = Cell::inCurrentWarehouse()->findOrFail($cellId);

        $leftovers = Leftover::with(
            [
                'container',
                'goods.barcodes',
                'goods.packages',
                'package',
                'goods.measurement_unit',
                'cell'
            ])
            ->where('cell_id', $cellId)
            ->where('goods_id', $goods->id)
            ->get();

        return [$cell, $leftovers];
    }

    /**
     * @param float $n
     * @return string
     */
    private static function fmtNum(float $n): string
    {
        $s = number_format($n, 3, '.', '');
        $s = rtrim($s, '0');
        $s = rtrim($s, '.');

        return $s === '' ? '0' : $s;
    }


    //TODO - Переробити під нормальну структуру
    /**
     * @param string $groupId
     * @return array
     */
    public function findLoggedLeftoversByGroup(string $groupId): array
    {
        $leftovers = Leftover::query()
            ->with([
                       'goods',
                       'goods.manufacturerCompany',
                       'goods.brandCompany',
                       'goods.barcodes',
                       'goods.packages',
                       'goods.measurement_unit',
                       'goods.company',
                       'goods.category',
                       'goods.categories',
                       'package',
                       'container',
                       'manualLogs' => function ($q) use ($groupId) {
                           $q->where('group_id', $groupId)
                               ->orderByDesc('created_at')
                               ->orderByDesc('id')
                               ->limit(1);
                       },
                   ])
            ->whereExists(function ($q) use ($groupId) {
                $q->select(DB::raw(1))
                    ->from('inventory_manual_leftover_logs as imll')
                    ->whereColumn('imll.leftover_id', 'leftovers.id')
                    ->where('imll.group_id', $groupId);
            })
            ->get();

        return [
            'leftovers' => $leftovers->map(function ($l) {
                $goods = $l->goods;
                $log = $l->manualLogs->first();
                $qtyAfter  = $log?->quantity_after;
                $qtyBefore = $log?->quantity_before;

                $current   = $qtyAfter  !== null ? (float) $qtyAfter  : (float) ($l->quantity ?? 0);
                $erp       = $qtyBefore !== null ? (float) $qtyBefore : (float) ($l->quantity ?? 0);

                $diff = $current - $erp;
                $divergence = $diff == 0.0 ? '0' : (($diff > 0 ? '+' : '') . self::fmtNum($diff));

                $respAt   = $log?->created_at;
                $respExec = $log?->executor;
                $respName = $respExec?->initial();

                $barcode = $goods && $goods->relationLoaded('barcodes')
                    ? optional($goods->barcodes->first())->barcode
                    : null;

                $manufacturerName = optional($goods->manufacturerCompany)->full_name
                    ?? optional($goods->company)->full_name
                    ?? null;

                $brandName = optional($goods->brandCompany)->full_name ?? null;

                $categoryName = optional($goods->category)->name
                    ?? optional($goods->categories)->name
                    ?? null;

                $container = $l->relationLoaded('container') ? $l->container : null;

                return [
                    'id'        => (string) $l->local_id,
                    'local_id'  => (string) ($l->id ?? ''),

                    'goods_name' => $goods->name ?? null,
                    'barcode'    => $barcode,
                    'name'       => [
                        'manufacturer' => $manufacturerName,
                        'category'     => $categoryName,
                        'brand'        => $brandName,
                    ],

                    'party'         => $l->batch ?? null,
                    'manufactured'  => self::dateStr($l->manufacture_date),
                    'expires_at'    => self::dateStr($l->bb_date),

                    'package' => [
                        'name' => optional($l->package)->name ?? null,
                    ],

                    'condition'     => isset($l->condition) ? (string) $l->condition : null,
                    'has_condition' => (bool) ($l->condition ?? false),

                    'container' => [
                        'code' => optional($container)->code ?? null,
                        'id'   => optional($container)->id ?? null,
                    ],

                    'qty'            => $current,
                    'leftovers_erp'  => $erp,
                    'divergence'     => $divergence,

                    'responsible_name' => $respName ?? '-',
                    'responsible_date' => $respAt ? $respAt->format('Y.m.d') : '-',
                    'responsible_time' => $respAt ? $respAt->format('H:i')    : '-',

                    'real' => true,
                ];
            })->values()->all(),
        ];
    }


    /**
     * @param $v
     * @return string|null
     */
    private static function dateStr($v): ?string
    {
        if ($v === null || $v === '') return null;

        try {
            return Carbon::parse($v)->toDateString();
        } catch (\Throwable $e) {
            return (string) $v;
        }
    }
}
