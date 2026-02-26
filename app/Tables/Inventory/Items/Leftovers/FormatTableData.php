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
 * FormatTableData.
 */
final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @param $paginator
     * @return array
     */
    #[\Override]
    public function formatData($paginator): array
    {
        /** @var LengthAwarePaginator $paginator */
        $items   = collect($paginator->items());
        $perPage = $paginator->perPage();
        $page    = $paginator->currentPage();
        $offset  = max(0, ($page - 1) * $perPage);

        if ($items->isEmpty()) {
            return [
                'total'      => 0,
                'containers' => [],
                'data'       => [],
            ];
        }

        $usersById = $this->buildUsersMapForItems($items);

        [$ilRows, $realLoQtyById] = $this->buildInventoryLeftoversGroups($items);

        $goods    = $this->mapById(
            'goods',
            ['id','name','manufacturer','brand','category_id','measurement_unit_id']
        );
        $packages = $this->mapById('packages', ['id','name', 'main_units_number']);

        $cellIds = $items->pluck('cell_id')->filter()->unique()->values();
        [$cellsById, $rowsById, $sectorsById, $zonesById, $whById] = $this->preloadCellHierarchy($cellIds);

        $goodsIdsAll = $ilRows
            ->flatMap(fn($g) => $g->pluck('goods_id'))
            ->filter()->unique()->values();

        [$barcodesByGoodsIdAll, $muById, $categoriesById, $packagesAllByGoods] =
            $this->buildGoodsDictionaries($goodsIdsAll, $goods);

        [$containersByRegisterId, $containers] = $this->buildContainersMap($ilRows);

        $seq  = $offset;
        $data = $items->values()->flatMap(function (InventoryItem $ii) use (
            &$seq,
            $ilRows,
            $cellsById,
            $rowsById,
            $sectorsById,
            $zonesById,
            $whById,
            $goods,
            $packages,
            $usersById,
            $realLoQtyById,
            $muById,
            $packagesAllByGoods,
            $categoriesById,
            $barcodesByGoodsIdAll,
            $containersByRegisterId
        ) {
            /** @var Collection $group */
            $group = $ilRows[$ii->id] ?? collect();

            return $group->values()->map(function ($il) use (
                &$seq,
                $ii,
                $cellsById,
                $rowsById,
                $sectorsById,
                $zonesById,
                $whById,
                $goods,
                $packages,
                $usersById,
                $realLoQtyById,
                $muById,
                $packagesAllByGoods,
                $categoriesById,
                $barcodesByGoodsIdAll,
                $containersByRegisterId
            ) {
                $rowId = (string) (++$seq);

                $g = $goods[$il->goods_id] ?? null;
                $p = $packages[$il->package_id] ?? null;

                [$warehouseName, $zoneName, $sectorName, $rowName, $cellCode, $cellId] =
                    $this->resolvePlacing($ii->cell_id, $cellsById, $rowsById, $sectorsById, $zonesById, $whById);

                $real        = !is_null($il->leftover_id);
                $hasCurrent  = !is_null($il->current_leftovers);
                $current     = $hasCurrent ? (int) $il->current_leftovers : null;
                $erp         = $real
                    ? (int) ($realLoQtyById[$il->leftover_id] ?? $il->quantity ?? 0)
                    : null;

                $divergence  = '-';

                if ($hasCurrent) {
                    $divergence = (int) $current - (int) $erp;
                }

                $divergenceDisplay = $divergence === '-'
                    ? '-'
                    : ($divergence === 0 ? '0' : ($divergence > 0 ? '+'.$divergence : (string) $divergence));

                $who = $usersById[$ii->update_id] ?? $usersById[$ii->creator_id] ?? null;

                $tsBase = $il->updated_at ?? $il->created_at ?? $ii->updated_at ?? $ii->created_at;
                $ts     = $tsBase
                    ? ($tsBase instanceof Carbon ? $tsBase : Carbon::parse($tsBase))
                    : null;

                $areaLabel    = $this->generateAreaLabel($il->area ?? null);
                $hasName      = !empty($who);
                $hasTs        = !empty($ts);

                $respNameOut  = $hasName ? $who : '-';
                $respDateOut  = $hasTs   ? $ts->format('Y.m.d') : '-';
                $respTimeOut  = $hasTs   ? $ts->format('H:i')    : '-';

                $muId = $g['measurement_unit_id'] ?? null;
                $measurementUnit = $muId
                    ? ['id' => (string)$muId, 'name' => ($muById[$muId] ?? null)]
                    : null;

                $packagesAll    = $packagesAllByGoods[$il->goods_id] ?? [];
                $packageCurrent = $il->package_id ? ([
                    'id'   => (string)$il->package_id,
                    'name' => ($packages[$il->package_id]['name'] ?? null),
                    'qty'  => ($packages[$il->package_id]['main_units_number'] ?? null),
                ]) : null;

                $barcode = $barcodesByGoodsIdAll[$il->goods_id] ?? $il->batch;

                return [
                    'id'               => $rowId,
                    'local_id'         => $il->id,
                    'leftover_id'      => (string) ($il->id),
                    'real'             => $real ? 1 : 0,
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
                        'code'      => $containersByRegisterId[$il->container_registers_id] ?? '',
                    ],
                    'manufactured'      => $il->manufacture_date ? (string) $il->manufacture_date : null,
                    'expiry'            => $il->bb_date          ? (string) $il->bb_date          : null,
                    'party'             => $il->batch            ? (string) $il->batch            : null,
                    'condition'         => $il->condition,
                    'package'           => $p['name'] ?? null,
                    'current_leftovers' => $current,
                    'leftovers_erp'     => $real ? $erp : $il->quantity,
                    'divergence'        => $divergenceDisplay,
                    'responsible_name'  => $respNameOut,
                    'responsible_date'  => $respDateOut,
                    'responsible_time'  => $respTimeOut,
                    'measurement_unit'  => $measurementUnit,
                    'package_current'   => $packageCurrent,
                    'packages_all'      => $packagesAll,
                ];
            });
        })->values();

        return [
            'total'      => $data->count(),
            'containers' => $containers,
            'data'       => $data->all(),
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
     * @param Collection $items
     * @return array
     */
    private function buildUsersMapForItems(Collection $items): array
    {
        $userIds = collect()
            ->merge($items->pluck('creator_id'))
            ->merge($items->pluck('update_id'))
            ->filter()->unique()->values();

        if ($userIds->isEmpty()) {
            return [];
        }

        return User::query()
            ->whereIn('id', $userIds)
            ->get()
            ->mapWithKeys(fn(User $u) => [$u->id => $u->initial()])
            ->all();
    }

    /**
     * @return array{0:Collection,1:array<string,int|float>}
     */
    private function buildInventoryLeftoversGroups(Collection $items): array
    {
        $ilRows = DB::table('inventory_leftovers as il')
            ->whereIn('il.inventory_item_id', $items->pluck('id')->all())
            ->select('il.*')
            ->orderBy('il.created_at', 'desc')
            ->get()
            ->groupBy('inventory_item_id');

        $allLeftoverIds = $ilRows
            ->flatMap(fn($g) => $g->pluck('leftover_id'))
            ->filter()->unique()->values();

        $realLoQtyById = $allLeftoverIds->isNotEmpty()
            ? DB::table('leftovers')
                ->whereIn('id', $allLeftoverIds)
                ->pluck('quantity', 'id')
                ->all()
            : [];

        return [$ilRows, $realLoQtyById];
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
        $cellsById      = [];
        $rowsById       = [];
        $sectorsById    = [];
        $zonesById      = [];
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
     * @param Collection $goodsIdsAll
     * @param array $goods
     * @return array|array[]
     */
    private function buildGoodsDictionaries(Collection $goodsIdsAll, array $goods): array
    {
        if ($goodsIdsAll->isEmpty()) {
            return [[], [], [], []];
        }

        $barcodesByGoodsIdAll = DB::table('barcodes')
            ->where('entity_type', 'App\Models\Goods')
            ->whereIn('entity_id', $goodsIdsAll)
            ->pluck('barcode', 'entity_id')
            ->all();

        $muIdsAll = $goodsIdsAll
            ->map(fn($gid) => $goods[$gid]['measurement_unit_id'] ?? null)
            ->filter()->unique()->values();

        $muById = $muIdsAll->isNotEmpty()
            ? MeasurementUnit::query()->whereIn('id', $muIdsAll)->pluck('name', 'id')->all()
            : [];

        $categoryIdsAll = $goodsIdsAll
            ->map(fn($gid) => $goods[$gid]['category_id'] ?? null)
            ->filter()->unique()->values();

        $categoriesById = $categoryIdsAll->isNotEmpty()
            ? DB::table('categories')->whereIn('id', $categoryIdsAll)->pluck('name', 'id')
                ->all()
            : [];

        $packagesAllByGoods = DB::table('packages')
            ->whereIn('goods_id', $goodsIdsAll)
            ->get(['id','name','goods_id', 'main_units_number'])
            ->groupBy('goods_id')
            ->map(function ($grp) {
                return $grp->map(fn($p) => [
                    'id'   => (string)$p->id,
                    'name' => $p->name,
                    'qty'  => $p->main_units_number
                ])->values()->all();
            })->all();

        return [$barcodesByGoodsIdAll, $muById, $categoriesById, $packagesAllByGoods];
    }

    /**
     * @param Collection $ilRows
     * @return array|array[]
     */
    private function buildContainersMap(Collection $ilRows): array
    {
        $allContainerRegisterIds = $ilRows
            ->flatMap(fn($g) => $g->pluck('container_registers_id'))
            ->filter()->unique()->values();

        if ($allContainerRegisterIds->isEmpty()) {
            return [[], []];
        }

        $containersByRegisterId = DB::table('container_registers as cr')
            ->join('containers as c', 'c.id', '=', 'cr.container_id')
            ->whereIn('cr.id', $allContainerRegisterIds)
            ->pluck('cr.code', 'cr.id')->all();

        $containers = collect($containersByRegisterId)
            ->map(fn($name, $registerId) => ['id' => (string)$registerId, 'name' => $name])
            ->values()
            ->all();

        return [$containersByRegisterId, $containers];
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
