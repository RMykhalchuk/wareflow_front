<?php

namespace App\Tables\Inventory\Items;

use App\Models\Entities\Inventory\InventoryItem;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * ZoneFormatTableData.
 */
final class ZoneFormatTableData extends AbstractFormatTableData
{
    /**
     * @param string $inventoryId
     */
    public function __construct(
        private readonly string $inventoryId
    ) {
    }

    /**
     * @param mixed $paginator
     * @return array
     */
    #[\Override]
    public function formatData($paginator): array
    {
        /** @var LengthAwarePaginator $paginator */
        $collection = collect($paginator->items());

        if ($collection->isEmpty()) {
            $inv = DB::table('inventories')
                ->where('id', $this->inventoryId)
                ->first(['id', 'local_id']);

            return [
                'inventory' => $inv,
                'total'     => 0,
                'zones'     => [],
            ];
        }

        /** @var Collection|string[] $cellIds */
        $cellIds = $collection->pluck('cell_id')->filter()->unique()->values();
        [$cellsById, $rowsById, $sectorsById, $zonesById] = $this->preloadCellHierarchy($cellIds);

        $zonesMap = [];

        foreach ($collection as $ii) {
            /** @var InventoryItem $ii */
            $cellId = $ii->cell_id;

            if (!$cellId || !isset($cellsById[$cellId])) {
                continue;
            }

            $cellObj  = (object) $cellsById[$cellId];
            $cellCode = $cellObj->code ?? null;

            $zoneName = null;
            $type     = $cellObj->parent_type;
            $id       = $cellObj->model_id;

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

            $zoneKey = $zoneName ?? '—';

            $statusEnum  = $ii->status;
            $statusRaw   = is_numeric($ii->status)
                ? (int) $ii->status
                : (is_numeric($statusEnum?->value ?? null) ? (int) $statusEnum->value : null);

            $statusValue = $statusRaw ?? ($statusEnum?->value ?? null);
            $statusLabel = $statusEnum?->label() ?? 'до інвентаризації';

            $zonesMap[$zoneKey] ??= [];
            $zonesMap[$zoneKey][] = [
                'inventory_item_id' => $ii->id,
                'cell'              => $cellCode,
                'status_value'      => is_null($statusRaw) ? null : (int) $statusRaw,
                'status_label'      => $statusLabel,
            ];
        }

        $zones = collect($zonesMap)
            ->sortKeys()
            ->map(function (array $items, string $zone) {
                $unique = collect($items)
                    ->unique('inventory_item_id')
                    ->sortBy(fn ($r) => [$r['cell'] ?? '~~~', (string) $r['inventory_item_id']])
                    ->values();

                $total = (int) $unique->count();

                $countsByStatus = [];

                foreach ($unique as $row) {
                    $sv = $row['status_value'];

                    if (is_int($sv)) {
                        $countsByStatus[$sv] = ($countsByStatus[$sv] ?? 0) + 1;
                    } else {
                        $countsByStatus['null'] = ($countsByStatus['null'] ?? 0) + 1;
                    }
                }

                $status2 = (int) ($countsByStatus[2] ?? 0);

                return [
                    'zone'             => $zone,
                    'total'            => $total,
                    'status_2'         => $status2,
                    'counts_by_status' => $countsByStatus,
                    'cells'            => $unique->map(fn ($r) => [
                        'inventory_item_id' => $r['inventory_item_id'],
                        'cell'              => $r['cell'],
                        'status'            => [
                            'value' => $r['status_value'],
                            'label' => $r['status_label'],
                        ],
                    ])->all(),
                ];
            })
            ->values()
            ->all();

        $inv = DB::table('inventories')
            ->where('id', $this->inventoryId)
            ->first(['id', 'local_id']);

        return [
            'inventory' => $inv,
            'total'     => (int) $paginator->total(),
            'zones'     => $zones,
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
     * @param Collection $cellIds
     * @return array{0:array,1:array,2:array,3:array}
     */
    private function preloadCellHierarchy(Collection $cellIds): array
    {
        $cellsById   = [];
        $rowsById    = [];
        $sectorsById = [];
        $zonesById   = [];

        if ($cellIds->isEmpty()) {
            return [$cellsById, $rowsById, $sectorsById, $zonesById];
        }

        $cells = DB::table('cells')
            ->whereIn('id', $cellIds)
            ->get(['id','code','parent_type','model_id'])
            ->keyBy('id');

        $cellsById = $cells->all();

        $rowIds    = $cells->where('parent_type', 'row')->pluck('model_id')->unique()->values();
        $sectorIds = $cells->where('parent_type', 'sector')->pluck('model_id')->unique()->values();
        $zoneIds   = $cells->where('parent_type', 'zone')->pluck('model_id')->unique()->values();

        if ($rowIds->isNotEmpty()) {
            $rows = DB::table('rows')
                ->whereIn('id', $rowIds)
                ->get(['id','name','sector_id'])
                ->keyBy('id');

            $rowsById   = $rows->all();
            $sectorIds  = $sectorIds->merge($rows->pluck('sector_id'))->filter()->unique()->values();
        }

        if ($sectorIds->isNotEmpty()) {
            $sectors = DB::table('sectors')
                ->whereIn('id', $sectorIds)
                ->get(['id','name','zone_id'])
                ->keyBy('id');

            $sectorsById = $sectors->all();
            $zoneIds     = $zoneIds->merge($sectors->pluck('zone_id'))->filter()->unique()->values();
        }

        if ($zoneIds->isNotEmpty()) {
            $zones = DB::table('warehouse_zones')
                ->whereIn('id', $zoneIds)
                ->get(['id','name'])
                ->keyBy('id');

            $zonesById = $zones->all();
        }

        return [$cellsById, $rowsById, $sectorsById, $zonesById];
    }
}
