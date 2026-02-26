<?php

namespace App\Tables\Inventory\Items\Leftovers;

use App\Models\Dictionaries\MeasurementUnit;
use App\Models\Entities\Inventory\InventoryItem;
use App\Models\User;
use App\Tables\Table\AbstractFormatTableData;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * FormatTableDataByItem.
 */
final class FormatTableDataByItem extends AbstractFormatTableData
{
    /**
     * @param string $inventoryItemId
     */
    public function __construct(
        private readonly string $inventoryItemId
    ) {
    }

    /**
     * @param $paginator
     * @return array
     */
    #[\Override]
    public function formatData($paginator): array
    {
        /** @var LengthAwarePaginator $paginator */
        $rows    = collect($paginator->items());
        $total   = (int) $paginator->total();
        $perPage = $paginator->perPage();
        $page    = $paginator->currentPage();

        if ($rows->isEmpty()) {
            $inventoryItemPayload = $this->buildInventoryItemPayload();

            return [
                'inventory_item' => $inventoryItemPayload,
                'total'          => 0,
                'containers'     => [],
                'data'           => [],
            ];
        }

        $goods    = $this->mapById(
            'goods',
            ['id','name','manufacturer','brand','category_id','measurement_unit_id']
        );
        $packages = $this->mapById('packages', ['id','name', 'main_units_number']);

        $userIds = $rows->pluck('item_update_id')
            ->merge($rows->pluck('item_creator_id'))
            ->filter()->unique()->values();

        $users = [];

        if ($userIds->isNotEmpty()) {
            $users = User::query()
                ->whereIn('id', $userIds)
                ->get()
                ->mapWithKeys(fn(User $u) => [$u->id => $u->initial()])
                ->all();
        }

        $cellIds = $rows->pluck('cell_id')->filter()->unique()->values();
        [$cellsById, $rowsById, $sectorsById, $zonesById, $whById] = $this->preloadCellHierarchy($cellIds);

        $leftoverIds = $rows->pluck('leftover_id')->filter()->unique()->values();
        $realLoQtyById = $leftoverIds->isNotEmpty()
            ? DB::table('leftovers')
                ->whereIn('id', $leftoverIds)
                ->pluck('quantity', 'id')
                ->all()
            : [];

        $goodsIds = $rows->pluck('goods_id')->filter()->unique()->values();

        $barcodesByGoodsId = $goodsIds->isNotEmpty()
            ? DB::table('barcodes')
                ->where('entity_type', 'goods')
                ->whereIn('entity_id', $goodsIds)
                ->pluck('barcode', 'entity_id')
                ->all()
            : [];

        $muIds = $goodsIds
            ->map(fn($gid) => $goods[$gid]['measurement_unit_id'] ?? null)
            ->filter()
            ->unique()
            ->values();

        $muById = $muIds->isNotEmpty()
            ? MeasurementUnit::query()->whereIn('id', $muIds)->pluck('name', 'id')->all()
            : [];

        $categoryIds = $goodsIds
            ->map(fn($gid) => $goods[$gid]['category_id'] ?? null)
            ->filter()->unique()->values();

        $categoriesById = $categoryIds->isNotEmpty()
            ? DB::table('categories')->whereIn('id', $categoryIds)->pluck('name', 'id')
                ->all()
            : [];

        $packagesAllByGoods = $goodsIds->isNotEmpty()
            ? DB::table('packages')
                ->whereIn('goods_id', $goodsIds)
                ->get(['id','name','goods_id', 'main_units_number'])
                ->groupBy('goods_id')
                ->map(function ($grp) {
                    return $grp->map(fn($p) => [
                        'id'   => (string)$p->id,
                        'name' => $p->name,
                        'qty'  => $p->main_units_number,
                    ])->values()->all();
                })->all()
            : [];

        $containerRegisterIds = $rows->pluck('container_registers_id')->filter()->unique()->values();
        $containersByRegisterId = $containerRegisterIds->isNotEmpty()
            ? DB::table('container_registers as cr')
                ->join('containers as c', 'c.id', '=', 'cr.container_id')
                ->whereIn('cr.id', $containerRegisterIds)
                ->pluck('cr.code', 'cr.id')->all()
            : [];

        $containers = collect($containersByRegisterId)
            ->map(fn($name, $registerId) => ['id' => (string)$registerId, 'name' => $name])
            ->values()
            ->all();

        $data = $rows->values()->map(function ($r, int $k) use (
            $page,
            $perPage,
            $cellsById,
            $rowsById,
            $sectorsById,
            $zonesById,
            $whById,
            $goods,
            $packages,
            $users,
            $realLoQtyById,
            $muById,
            $packagesAllByGoods,
            $categoriesById,
            $barcodesByGoodsId
        ) {
            $g = $goods[$r->goods_id] ?? null;
            $p = $packages[$r->package_id] ?? null;

            [$warehouseName, $zoneName, $sectorName, $rowName, $cellCode, $cellId] =
                $this->resolvePlacing($r->cell_id, $cellsById, $rowsById, $sectorsById, $zonesById, $whById);

            $real        = !is_null($r->leftover_id);
            $hasCurrent  = !is_null($r->current_leftovers);
            $current     = $hasCurrent ? (int) $r->current_leftovers : null;

            $erp         = $real
                ? (int) ($realLoQtyById[$r->leftover_id] ?? $r->quantity ?? 0)
                : null;

            $divergence  = '-';

            if ($hasCurrent) {
                $divergence = (int) $current - (int) $erp;
            }

            $divergenceDisplay = $divergence === '-'
                ? '-'
                : ($divergence === 0 ? '0' : ($divergence > 0 ? '+'.$divergence : (string) $divergence));

            $who = $users[$r->item_update_id] ?? $users[$r->item_creator_id] ?? null;
            $ts  = $r->leftover_updated_at ?? $r->item_updated_at ?? null;

            $areaLabel    = $this->generateAreaLabel($r->area);
            $noCurrent    = !$hasCurrent;
            $hasName      = !$noCurrent && !empty($who);
            $hasTs        = !$noCurrent && !empty($ts);

            $respNameOut  = $hasName ? $who : '-';
            $respDateOut  = $hasTs   ? Carbon::parse($ts)->format('Y.m.d') : '-';
            $respTimeOut  = $hasTs   ? Carbon::parse($ts)->format('H:i')    : '-';

            $muId = $g['measurement_unit_id'] ?? null;
            $measurementUnit = $muId
                ? ['id' => (string)$muId, 'name' => ($muById[$muId] ?? null)]
                : null;

            $packagesAll   = $packagesAllByGoods[$r->goods_id] ?? [];
            $packageCurrent = $r->package_id ? ([
                'id'   => (string)$r->package_id,
                'name' => ($packages[$r->package_id]['name'] ?? null),
                'qty'  => ($packages[$r->package_id]['main_units_number'] ?? null),
            ]) : null;

            $barcode = $barcodesByGoodsId[$r->goods_id] ?? $r->batch;

            return [
                'id'             => (string) (($page - 1) * $perPage + $k + 1),
                'local_id'       => (string) ($r->il_id),
                'leftover_id'    => (string) ($r->il_id),
                'real'           => $real ? 1 : 0,
                'name' => [
                    'title'        => $g['name'] ?? null,
                    'barcode'      => $barcode,
                    'manufacturer' => $g['manufacturer'] ?? null,
                    'category'     => isset($g['category_id'])
                        ? ($categoriesById[$g['category_id']] ?? null)
                        : null,
                    'brand'        => $g['brand'] ?? null,
                    'good_id'      => $g['id'] ?? null,
                ],
                'placing' => [
                    'cell_id'   => $cellId,
                    'pallet'    => '',
                    'warehouse' => $warehouseName,
                    'zone'      => $zoneName,
                    'sector'    => $sectorName,
                    'row'       => $rowName,
                    'cell'      => $cellCode,
                    'code'      => '',
                ],
                'manufactured'      => $r->manufacture_date ? (string) $r->manufacture_date : null,
                'expiry'            => $r->bb_date          ? (string) $r->bb_date          : null,
                'party'             => $r->batch            ? (string) $r->batch            : null,
                'condition'         => (int) $r->condition,
                'package'           => $p['name'] ?? null,
                'current_leftovers' => $current,
                'leftovers_erp'     => $real ? $erp : $r->quantity,
                'divergence'        => $divergenceDisplay,
                'responsible_name'  => $respNameOut,
                'responsible_date'  => $respDateOut,
                'responsible_time'  => $respTimeOut,
                'measurement_unit'  => $measurementUnit,
                'package_current'   => $packageCurrent,
                'packages_all'      => $packagesAll,
            ];
        })->all();

        $inventoryItemPayload = $this->buildInventoryItemPayload();

        return [
            'inventory_item' => $inventoryItemPayload,
            'total'          => $total,
            'containers'     => $containers,
            'data'           => $data,
        ];
    }

    /**
     * @param $relation
     * @return string
     */
    public function relationsSelectByField($relation): string
    {
        return '*';
    }

    /**
     * @return array|null
     */
    private function buildInventoryItemPayload(): ?array
    {
        /** @var InventoryItem|null $inventoryItem */
        $inventoryItem = InventoryItem::query()
            ->select(['id', 'cell_id'])
            ->with(['cell' => function ($q) {
                $q->select('id', 'code');
            }])
            ->where('id', $this->inventoryItemId)
            ->first();

        if (!$inventoryItem) {
            return null;
        }

        return [
            'id'   => (string) $inventoryItem->id,
            'cell' => $inventoryItem->cell ? [
                'id'   => (string) $inventoryItem->cell->id,
                'code' => $inventoryItem->cell->code,
            ] : null,
        ];
    }

    /**
     * @param string $table
     * @param array $columns
     * @return array
     */
    private function mapById(string $table, array $columns): array
    {
        return DB::table($table)
            ->get($columns)
            ->keyBy('id')
            ->map(fn($row) => (array) $row)
            ->all();
    }

    /**
     * @param Collection $cellIds
     * @return array|array[]
     */
    private function preloadCellHierarchy(Collection $cellIds): array
    {
        $cellsById    = [];
        $rowsById     = [];
        $sectorsById  = [];
        $zonesById    = [];
        $warehousesById = [];

        if ($cellIds->isEmpty()) {
            return [$cellsById, $rowsById, $sectorsById, $zonesById, $warehousesById];
        }

        $cells = DB::table('cells')
            ->whereIn('id', $cellIds)
            ->get(['id', 'code', 'parent_type', 'model_id'])
            ->keyBy('id');

        $cellsById = $cells->all();

        $rowIds    = $cells->where('parent_type', 'row')->pluck('model_id')->unique()->values();
        $sectorIds = $cells->where('parent_type', 'sector')->pluck('model_id')->unique()->values();
        $zoneIds   = $cells->where('parent_type', 'zone')->pluck('model_id')->unique()->values();

        if ($rowIds->isNotEmpty()) {
            $rows = DB::table('rows')
                ->whereIn('id', $rowIds)
                ->get(['id', 'name', 'sector_id'])
                ->keyBy('id');

            $rowsById  = $rows->all();
            $sectorIds = $sectorIds->merge($rows->pluck('sector_id'))->filter()->unique()->values();
        }

        if ($sectorIds->isNotEmpty()) {
            $sectors = DB::table('sectors')
                ->whereIn('id', $sectorIds)
                ->get(['id', 'name', 'zone_id'])
                ->keyBy('id');

            $sectorsById = $sectors->all();
            $zoneIds     = $zoneIds->merge($sectors->pluck('zone_id'))->filter()->unique()->values();
        }

        if ($zoneIds->isNotEmpty()) {
            $zones = DB::table('warehouse_zones')
                ->whereIn('id', $zoneIds)
                ->get(['id', 'name', 'warehouse_id'])
                ->keyBy('id');

            $zonesById = $zones->all();

            $whIds = $zones->pluck('warehouse_id')->filter()->unique()->values();

            if ($whIds->isNotEmpty()) {
                $warehousesById = DB::table('warehouses')
                    ->whereIn('id', $whIds)
                    ->get(['id', 'name'])
                    ->keyBy('id')
                    ->all();
            }
        }

        return [$cellsById, $rowsById, $sectorsById, $zonesById, $warehousesById];
    }

    /**
     * @param string|null $cellId
     * @param array $cellsById
     * @param array $rowsById
     * @param array $sectorsById
     * @param array $zonesById
     * @param array $whById
     * @return array
     */
    private function resolvePlacing(
        ?string $cellId,
        array $cellsById,
        array $rowsById,
        array $sectorsById,
        array $zonesById,
        array $whById
    ): array {
        $cell          = $cellId && isset($cellsById[$cellId]) ? (object) $cellsById[$cellId] : null;
        $zoneName      = null;
        $sectorName    = null;
        $rowName       = null;
        $warehouseName = null;
        $cellCode      = $cell->code ?? null;

        if (!$cell) {
            return [$warehouseName, $zoneName, $sectorName, $rowName, $cellCode, $cellId];
        }

        if ($cell->parent_type === 'row') {
            $row    = $rowsById[$cell->model_id] ?? null;
            $sector = $row ? ($sectorsById[$row->sector_id] ?? null) : null;
            $zone   = $sector ? ($zonesById[$sector->zone_id] ?? null) : null;
            $wh     = $zone ? ($whById[$zone->warehouse_id] ?? null) : null;

            $rowName       = $row->name    ?? null;
            $sectorName    = $sector->name ?? null;
            $zoneName      = $zone->name   ?? null;
            $warehouseName = $wh->name     ?? null;
        } elseif ($cell->parent_type === 'sector') {
            $sector = $sectorsById[$cell->model_id] ?? null;
            $zone   = $sector ? ($zonesById[$sector->zone_id] ?? null) : null;
            $wh     = $zone ? ($whById[$zone->warehouse_id] ?? null) : null;

            $sectorName    = $sector->name ?? null;
            $zoneName      = $zone->name   ?? null;
            $warehouseName = $wh->name     ?? null;
        } elseif ($cell->parent_type === 'zone') {
            $zone   = $zonesById[$cell->model_id] ?? null;
            $wh     = $zone ? ($whById[$zone->warehouse_id] ?? null) : null;

            $zoneName      = $zone->name ?? null;
            $warehouseName = $wh->name   ?? null;
        }

        return [$warehouseName, $zoneName, $sectorName, $rowName, $cellCode, $cellId];
    }

    /**
     * @param mixed $area
     * @return string
     */
    private function generateAreaLabel(mixed $area): string
    {
        if (is_array($area)) {
            return implode(', ', $area);
        }

        if (is_string($area)) {
            $trimmed = trim($area);

            if ($trimmed !== '' && ($trimmed[0] === '[' || $trimmed[0] === '{')) {
                $decoded = json_decode($trimmed, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return implode(', ', $decoded);
                }
            }
        }

        return (string) $area;
    }
}
