<?php

namespace App\Tables\Inventory\Items;

use App\Models\Entities\Inventory\InventoryItem;
use App\Models\User;
use App\Tables\Table\AbstractFormatTableData;
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
        $collection = collect($paginator->items());

        $perPage = $paginator->perPage();
        $page    = $paginator->currentPage();
        $offset  = max(0, ($page - 1) * $perPage);

        $ilAggByItem = $this->buildInventoryLeftoversAgg($collection);
        $usersById   = $this->buildUsersMap($collection);

        [
            'cellsById'    => $cellsById,
            'rowsById'     => $rowsById,
            'sectorsById'  => $sectorsById,
            'zonesById'    => $zonesById,
            'warehousesById' => $whById,
        ] = $this->buildCellHierarchy($collection);

        $data = $this->mapItemsToRows(
            $collection,
            $offset,
            $cellsById,
            $rowsById,
            $sectorsById,
            $zonesById,
            $ilAggByItem,
            $usersById
        );

        $data = $this->sortRows($data);

        return [
            'total' => (int) $paginator->total(),
            'data'  => $data->all(),
        ];
    }

    /**
     * @param Collection $collection
     * @return array
     */
    private function buildInventoryLeftoversAgg(Collection $collection): array
    {
        $itemIds = $collection->pluck('id')->filter()->unique()->values();
        $ilAggByItem = [];

        if ($itemIds->isNotEmpty()) {
            $ilAggByItem = DB::table('inventory_leftovers')
                ->whereIn('inventory_item_id', $itemIds)
                ->select(
                    'inventory_item_id',
                    DB::raw('COUNT(*)::int AS cnt'),
                    DB::raw('SUM(CASE WHEN current_leftovers IS NOT NULL THEN 1 ELSE 0 END)::int AS filled')
                )
                ->groupBy('inventory_item_id')
                ->get()
                ->keyBy('inventory_item_id')
                ->all();
        }

        return $ilAggByItem;
    }

    /**
     * @param Collection $collection
     * @return array
     */
    private function buildUsersMap(Collection $collection): array
    {
        $userIds = collect()
            ->merge($collection->pluck('creator_id'))
            ->merge($collection->pluck('update_id'))
            ->filter()
            ->unique()
            ->values();

        if ($userIds->isEmpty()) {
            return [];
        }

        return User::query()
            ->whereIn('id', $userIds)
            ->get()
            ->mapWithKeys(function (User $u) {
                return [$u->id => $u->initial()];
            })
            ->all();
    }

    /**
     * @param Collection $collection
     * @return array
     */
    private function buildCellHierarchy(Collection $collection): array
    {
        $cellIds   = $collection->pluck('cell_id')->filter()->unique()->values();

        $cellsById    = [];
        $rowsById     = [];
        $sectorsById  = [];
        $zonesById    = [];
        $warehousesById = [];

        if ($cellIds->isEmpty()) {
            return compact('cellsById', 'rowsById', 'sectorsById', 'zonesById', 'warehousesById');
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

            $rowsById    = $rows->all();
            $sectorIds   = $sectorIds->merge($rows->pluck('sector_id'))->filter()->unique()->values();
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

        return compact('cellsById', 'rowsById', 'sectorsById', 'zonesById', 'warehousesById');
    }

    /**
     * @param Collection $collection
     * @param int $offset
     * @param array $cellsById
     * @param array $rowsById
     * @param array $sectorsById
     * @param array $zonesById
     * @param array $ilAggByItem
     * @param array $usersById
     * @return Collection
     */
    private function mapItemsToRows(
        Collection $collection,
        int $offset,
        array $cellsById,
        array $rowsById,
        array $sectorsById,
        array $zonesById,
        array $ilAggByItem,
        array $usersById
    ): Collection {
        return $collection->values()->map(
            function (InventoryItem $ii, int $k) use (
                $offset,
                $cellsById,
                $rowsById,
                $sectorsById,
                $zonesById,
                $ilAggByItem,
                $usersById
            ) {
                [$zoneName, $cellCode] = $this->resolveZoneAndCell(
                    $ii->cell_id,
                    $cellsById,
                    $rowsById,
                    $sectorsById,
                    $zonesById
                );

                $agg        = $ilAggByItem[$ii->id] ?? null;
                $totalIl    = $agg ? (int) $agg->cnt : 0;
                $statusEnum = $ii->status;

                $statusValue = $statusEnum?->value ?? 'before_inventory';
                $statusLabel = $statusEnum?->label() ?? 'до інвентаризації';

                $statusRaw = is_numeric($ii->status)
                    ? (int) $ii->status
                    : (is_numeric($statusEnum?->value ?? null) ? (int) $statusEnum->value : null);

                $leftoversCount = $totalIl;

                $whoInitial = $usersById[$ii->update_id] ?? $usersById[$ii->creator_id] ?? null;
                $whoToShow  = ($statusRaw === 2) ? $whoInitial : null;

                $ts          = $ii->updated_at ?: $ii->created_at;
                $areaLabel   = $this->generateAreaLabel($ii->area);
                $showInvented = !empty($whoToShow) && ($statusRaw !== 1);

                return [
                    'id'       => (string) ($offset + $k + 1),
                    'local_id' => $ii->id,
                    'zone'     => $zoneName,
                    'cell'     => $cellCode,
                    'status'   => [
                        'value' => $statusValue,
                        'label' => $statusLabel,
                    ],
                    'leftovers' => [
                        'quantity' => $leftoversCount,
                        'id'       => null,
                    ],
                    'invented' => [
                        'name' => $showInvented ? $whoToShow : '-',
                        'date' => ($showInvented && $ts) ? $ts->format('Y.m.d') : '-',
                        'time' => ($showInvented && $ts) ? $ts->format('H:i')    : '-',
                    ],
                ];
            }
        );
    }

    /**
     * @param string|null $cellId
     * @param array $cellsById
     * @param array $rowsById
     * @param array $sectorsById
     * @param array $zonesById
     * @return array
     */
    private function resolveZoneAndCell(
        ?string $cellId,
        array $cellsById,
        array $rowsById,
        array $sectorsById,
        array $zonesById
    ): array {
        $cell     = $cellId && isset($cellsById[$cellId]) ? (object) $cellsById[$cellId] : null;
        $zoneName = null;
        $cellCode = $cell->code ?? null;

        if (!$cell) {
            return [$zoneName, $cellCode];
        }

        $type = $cell->parent_type;
        $id   = $cell->model_id;

        if ($type === 'row') {
            $row    = $rowsById[$id] ?? null;
            $sector = $row ? ($sectorsById[$row->sector_id] ?? null) : null;
            $zone   = $sector ? ($zonesById[$sector->zone_id] ?? null) : null;
            $zoneName = $zone->name ?? null;
        } elseif ($type === 'sector') {
            $sector   = $sectorsById[$id] ?? null;
            $zone     = $sector ? ($zonesById[$sector->zone_id] ?? null) : null;
            $zoneName = $zone->name ?? null;
        } elseif ($type === 'zone') {
            $zone     = $zonesById[$id] ?? null;
            $zoneName = $zone->name ?? null;
        }

        return [$zoneName, $cellCode];
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
     * @param Collection $data
     * @return Collection
     */
    private function sortRows(Collection $data): Collection
    {
        return $data->sortBy(function (array $row) {
            $zone = $row['zone'] ?? '~~~';
            $cell = $row['cell'] ?? '~~~';

            return [$zone, $cell, $row['id']];
        })->values();
    }

    /**
     * @param array|null $area
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
