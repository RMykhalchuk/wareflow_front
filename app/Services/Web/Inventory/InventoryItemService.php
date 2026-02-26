<?php

namespace App\Services\Web\Inventory;

use App\Enums\Cells\CellStatus;
use App\Enums\ContainerRegister\ContainerRegisterStatus;
use App\Models\Dictionaries\MeasurementUnit;
use App\Models\Entities\Inventory\Inventory;
use App\Models\Entities\Inventory\InventoryItem;
use App\Models\Entities\Leftover\Leftover;
use App\Models\User;
use App\Services\Terminal\Inventory\InventoryItemServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Tables\Inventory\Items\TableFacade as InventoryItemTableFacade;
use App\Tables\Inventory\Items\Leftovers\TableFacade as LeftoverTableFacade;

/**
 * Inventory Item Service.
 */
class InventoryItemService implements InventoryItemServiceInterface
{
    /** {@inheritdoc} */
    public function get(string $id): InventoryItem
    {
        /** @var InventoryItem $item */
        return InventoryItem::query()->findOrFail($id);
    }

    /** {@inheritdoc} */
    public function listByInventory(string $inventoryId, int $perPage = 50, int $page = 1): LengthAwarePaginator
    {
        $raw = trim($inventoryId);

        if ($raw !== '' && $raw[0] === '{') {
            $decoded = json_decode($raw, true);

            if (json_last_error() === JSON_ERROR_NONE && !empty($decoded['id'])) {
                $inventoryId = (string) $decoded['id'];
            }
        }

        return InventoryItem::query()
            ->where('inventory_id', $inventoryId)
            ->with([
                'creator:id,name',
                'updater:id,name',
            ])
            ->orderByDesc('created_at')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /** {@inheritdoc} */
    public function create(array $data): InventoryItem
    {
        /** @var InventoryItem $item */
        $item = InventoryItem::query()->create($data);

        return $item->refresh();
    }

    /** {@inheritdoc} */
    public function store(array $data): InventoryItem
    {
        return $this->create($data);
    }

    /** {@inheritdoc} */
    public function update(InventoryItem $item, array $data): InventoryItem
    {
        $item->fill($data)->save();

        return $item->refresh();
    }

    /** {@inheritdoc} */
    public function delete(InventoryItem $item): void
    {
        $item->delete();
    }

    /**
     * @param Inventory $inventory
     * @param bool $skipExisting
     * @return int
     * @throws \Throwable
     */
    public function seedFromInventory(Inventory $inventory, bool $skipExisting = true): int
    {
        $cellsQB   = $this->cellsQueryForInventory($inventory);
        $mode      = (int) ($inventory->process_cell ?? 0);

        $cellsQB->whereNull('cells.deleted_at')
            ->where('cells.status', 1);

        if ($mode === 2) {
            $cellsQB->whereNotExists(function ($sq) {
                $sq->select(DB::raw(1))
                    ->from('leftovers')
                    ->whereNull('leftovers.deleted_at')
                    ->whereColumn('leftovers.cell_id', 'cells.id');
            });
        } elseif ($mode === 3) {
            $cellsQB->whereExists(function ($sq) {
                $sq->select(DB::raw(1))
                    ->from('leftovers')
                    ->whereNull('leftovers.deleted_at')
                    ->whereColumn('leftovers.cell_id', 'cells.id');
            });
        }

        $now = now();

        $actorId = \Auth::id()
            ?? $inventory->creator_id
            ?? (string) DB::table('users')->value('id');

        $insertedItems = 0;

        DB::transaction(function () use ($cellsQB, $inventory, $now, $actorId, &$insertedItems) {
            $cellsQB->orderBy('cells.id')->chunk(1000, function (Collection $cells) use (
                $inventory,
                $now,
                $actorId,
                &$insertedItems
            ) {
                if ($cells->isEmpty()) return;

                $cellIds = $cells->pluck('id')->values()->all();

                $before = DB::table('inventory_items')
                    ->where('inventory_id', $inventory->id)
                    ->whereIn('cell_id', $cellIds)
                    ->count();

                $itemsPayload = [];

                foreach ($cells as $cell) {
                    $itemsPayload[] = [
                        'id'           => (string) Str::uuid(),
                        'inventory_id' => (string) $inventory->id,
                        'cell_id'      => (string) $cell->id,
                        'creator_id'   => (string) $actorId,
                        'qty'          => 0,
                        'real_qty'     => 0,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }

                DB::table('inventory_items')->upsert(
                    $itemsPayload,
                    ['inventory_id','cell_id'],
                    []
                );

                $after = DB::table('inventory_items')
                    ->where('inventory_id', $inventory->id)
                    ->whereIn('cell_id', $cellIds)
                    ->count();

                $insertedItems += max(0, $after - $before);

                $itemIdByCell = DB::table('inventory_items')
                    ->where('inventory_id', $inventory->id)
                    ->whereIn('cell_id', $cellIds)
                    ->pluck('id', 'cell_id')
                    ->all();

                $realLeftoversQB = DB::table('leftovers')
                    ->whereIn('cell_id', $cellIds)
                    ->whereNull('deleted_at');

                if ((int) ($inventory->process_cell ?? 0) === 3 && !empty($inventory->nomenclature_id)) {
                    $realLeftoversQB->where('goods_id', $inventory->nomenclature_id)
                        ->orderBy('created_at', 'desc');
                }

                $realLeftovers = $realLeftoversQB->get([
                    'id', 'cell_id', 'goods_id', 'package_id', 'quantity',
                    'batch', 'manufacture_date', 'bb_date', 'has_condition'
                ]);

                if ($realLeftovers->isEmpty()) return;

                $ilPayload = [];

                foreach ($realLeftovers as $lo) {
                    $inventoryItemId = $itemIdByCell[$lo->cell_id] ?? null;

                    if (!$inventoryItemId) continue;

                    $ilPayload[] = [
                        'id'                 => (string) Str::uuid(),
                        'inventory_item_id'  => (string) $inventoryItemId,
                        'leftover_id'        => (string) $lo->id,
                        'goods_id'           => (string) $lo->goods_id,
                        'package_id'         => (string) $lo->package_id,
                        'quantity'           => (int)    $lo->quantity,
                        'batch'              => (string) ($lo->batch ?? ''),
                        'manufacture_date'   => $lo->manufacture_date,
                        'bb_date'            => $lo->bb_date,
                        'creator_id'         => (string) $actorId,
                        'created_at'         => $now,
                        'updated_at'         => $now,
                        'condition'          => (bool) $lo->has_condition,
                    ];
                }

                if (!empty($ilPayload)) {
                    DB::table('inventory_leftovers')->upsert(
                        $ilPayload,
                        ['inventory_item_id','leftover_id'],
                        []
                    );
                }
            });
        });

        return $insertedItems;
    }

    /**
     * @param Inventory $inventory
     * @param array $filters
     * @param bool $skipExisting
     * @return int
     */
    public function seedFromLeftovers(Inventory $inventory, array $filters = [], bool $skipExisting = true): int
    {
        return 0;
    }

    /**
     * @param string $id
     * @param float|int $quantity
     * @return array
     * @throws \Throwable
     */
    public function correctQuantity(string $id, float|int $quantity): array
    {
        return DB::transaction(function () use ($id, $quantity) {
            /** @var InventoryItem $ii */
            $ii = InventoryItem::query()
                ->where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            $ii->real_qty = (int) $quantity;
            $ii->update_id = Auth::id();
            $ii->save();

            $current = $ii->qty ?? 0;
            $erp     = $ii->real_qty ?? 0;
            $diff    = $current - $erp;

            return [
                'status'            => 'ok',
                'item_id'           => $ii->id,
                'current_leftovers' => $current,
                'leftovers_erp'     => $erp,
                'divergence'        => $ii->real_qty === null
                    ? ''
                    : ($diff == 0 ? '0' : ($diff > 0 ? ('+' . $diff) : (string) $diff)),
            ];
        });
    }

    /**
     * Apply inventoried quantities to the real leftovers:
     * - If inventory_leftover has leftover_id (it's a real leftover) → just update that leftover's quantity.
     * - If inventory_leftover has NO leftover_id → create a new leftovers row as a copy (linked to the item’s cell),
     *   and attach its id back to inventory_leftovers.leftover_id.
     *
     * @param Inventory $inventory
     * @return int  Number of affected leftovers (updated + created)
     * @throws \Throwable
     */
    public function applyRealQtyToLeftoversByInventory(Inventory $inventory): int
    {
        $affected = 0;
        $now = now();

        DB::transaction(function () use ($inventory, $now, &$affected) {
            $actorCompanyId = \Auth::user()?->workingData()?->get()[0]?->company_id;
            $warehouseId    = $inventory->warehouse_id;
            $actorId        = \Auth::id()
                ?? $inventory->creator_id
                ?? (string) DB::table('users')->value('id');

            DB::table('inventory_leftovers as il')
                ->join('inventory_items as ii', 'ii.id', '=', 'il.inventory_item_id')
                ->where('ii.inventory_id', $inventory->id)
                ->orderBy('il.id')
                ->select([
                    'il.id as il_id',
                    'il.leftover_id',
                    'il.goods_id',
                    'il.package_id',
                    'il.quantity as il_qty',
                    'il.current_leftovers',
                    'il.batch',
                    'il.manufacture_date',
                    'il.bb_date',
                    'il.container_registers_id',
                    'ii.cell_id',
                ])
                ->chunk(1000, function ($rows) use (&$affected, $now, $actorId, $actorCompanyId, $warehouseId) {
                    if ($rows->isEmpty()) {
                        return;
                    }

                    foreach ($rows as $r) {
                        $newQty = is_null($r->current_leftovers)
                            ? ($r->il_qty ?? 0)
                            : (int) $r->current_leftovers;

                        if ($newQty === 0) {
                            if (!empty($r->leftover_id)) {
                                $leftover = Leftover::whereNull('deleted_at')->find($r->leftover_id);
                                if ($leftover) {
                                    $leftover->delete(); // fires LeftoverObserver::deleted() → оновлює статус контейнера
                                    $affected++;
                                }
                            }

                            continue;
                        }

                        if (!empty($r->leftover_id)) {
                            $affected += DB::table('leftovers')
                                ->where('id', $r->leftover_id)
                                ->whereNull('deleted_at')
                                ->update([
                                    'quantity'   => $newQty,
                                    'updated_at' => $now,
                                ]);
                        } else {
                            $newId = (string) Str::uuid();

                            DB::table('leftovers')->insert([
                                'id'                 => $newId,
                                'cell_id'            => (string) $r->cell_id,
                                'goods_id'           => (string) $r->goods_id,
                                'package_id'         => (string) $r->package_id,
                                'quantity'           => $newQty,
                                'batch'              => $r->batch ?? '',
                                'manufacture_date'   => $r->manufacture_date,
                                'bb_date'            => $r->bb_date,
                                'creator_company_id' => (string) $actorCompanyId,
                                'warehouse_id'       => (string) $warehouseId,
                                'container_id'       => $r->container_registers_id ? (string) $r->container_registers_id : null,
                                'created_at'         => $now,
                                'updated_at'         => $now,
                            ]);

                            DB::table('inventory_leftovers')
                                ->where('id', $r->il_id)
                                ->update([
                                    'leftover_id' => $newId,
                                    'updated_at'  => $now,
                                ]);

                            $affected++;
                        }
                    }
                });
        });

        return $affected;
    }

    /** {@inheritdoc} */
    public function recalculateContainerStatusesForInventory(Inventory $inventory): void
    {
        $containerIds = DB::table('inventory_leftovers as il')
            ->join('inventory_items as ii', 'ii.id', '=', 'il.inventory_item_id')
            ->where('ii.inventory_id', $inventory->id)
            ->whereNotNull('il.container_registers_id')
            ->distinct()
            ->pluck('il.container_registers_id');

        if ($containerIds->isEmpty()) {
            return;
        }

        $now = now();

        $containersWithLeftovers = DB::table('leftovers')
            ->whereIn('container_id', $containerIds)
            ->whereNull('deleted_at')
            ->distinct()
            ->pluck('container_id');

        $emptyContainerIds = $containerIds->diff($containersWithLeftovers);

        if ($emptyContainerIds->isNotEmpty()) {
            DB::table('container_registers')
                ->whereIn('id', $emptyContainerIds)
                ->where('status_id', '!=', ContainerRegisterStatus::DEACTIVATED->value)
                ->update(['status_id' => ContainerRegisterStatus::EMPTY->value, 'updated_at' => $now]);
        }

        if ($containersWithLeftovers->isNotEmpty()) {
            DB::table('container_registers')
                ->whereIn('id', $containersWithLeftovers)
                ->where('status_id', '!=', ContainerRegisterStatus::WITH_PRODUCT->value)
                ->where('status_id', '!=', ContainerRegisterStatus::DEACTIVATED->value)
                ->update(['status_id' => ContainerRegisterStatus::WITH_PRODUCT->value, 'updated_at' => $now]);
        }
    }

    /**
     * @param string $inventory
     * @param string $inventory_item
     * @return InventoryItem|Model|mixed
     */
    public function showItemFromInventory(string $inventory, string $inventory_item): mixed
    {
        return InventoryItem::query()
            ->whereKey($inventory_item)
            ->where('inventory_id', $inventory)
            ->firstOrFail();
    }

    /**
     * @param string $inventoryId
     * @param Request $request
     * @return array
     */
    public function gridItemsDataFromRequest(string $inventoryId, Request $request): array
    {
        return InventoryItemTableFacade::getFilteredData($inventoryId);
    }

    /**
     * @param string $inventoryId
     * @param Request $request
     * @return array
     */
    public function gridItemsDataByZone(string $inventoryId, Request $request): array
    {
        return InventoryItemTableFacade::getFilteredDataByZone($inventoryId);
    }

    /**
     * @param string $inventoryId
     * @param Request $request
     * @return array
     */
    public function gridLefoversDataFromRequest(string $inventoryId, Request $request): array
    {
        return LeftoverTableFacade::getFilteredDataByInventory($inventoryId);
    }

    /**
     * Legacy leftovers grid — MODE 2 (by inventory_item):
     * returns ONLY inventory_leftovers of a specific inventory_item (paged).
     *
     * @param string  $inventoryItemId
     * @param Request $request
     * @return array{total:int,data:array}
     */
    public function gridLefoversDataByItemFromRequest(string $inventoryItemId, Request $request): array
    {
        return LeftoverTableFacade::getFilteredDataByItem($inventoryItemId);
    }

    /**
     * @param string $inventoryItemId
     * @return array
     * @throws \Throwable
     */
    public function reset(string $inventoryItemId): array
    {
        $now    = now();
        $userId = \Auth::id();

        return DB::transaction(function () use ($inventoryItemId, $now, $userId) {
            $locked = DB::table('inventory_items as ii')
                ->join('inventories as inv', 'inv.id', '=', 'ii.inventory_id')
                ->where('ii.id', $inventoryItemId)
                ->lockForUpdate()
                ->select('ii.id as item_id', 'ii.inventory_id', 'inv.status as inventory_status')
                ->first();

            if (!$locked) {
                abort(404, 'Inventory item not found');
            }

            if ((int) $locked->inventory_status !== 2) {
                abort(409, 'Operation blocked: inventory status must be 2.');
            }

            $itemUpdated = DB::table('inventory_items')
                ->where('id', $inventoryItemId)
                ->update([
                    'area'       => null,
                    'status'     => 1,
                    'update_id'  => null,
                    'updated_at' => $now,
                ]);

            $clearedAreas = DB::table('inventory_leftovers')
                ->where('inventory_item_id', $inventoryItemId)
                ->update([
                    'area'       => null,
                    'updated_at' => $now,
                ]);

            $resetWithLeftover = DB::table('inventory_leftovers')
                ->where('inventory_item_id', $inventoryItemId)
                ->whereNotNull('leftover_id')
                ->update([
                    'current_leftovers' => null,
                    // 'creator_id'     => null,
                    'updated_at'        => $now,
                ]);

            $deletedWithoutLeftover = DB::table('inventory_leftovers')
                ->where('inventory_item_id', $inventoryItemId)
                ->whereNull('leftover_id')
                ->delete();

            return [
                'status'  => 'ok',
                'item_id' => (string) $inventoryItemId,
                'effects' => [
                    'inventory_item_updated'        => (bool) $itemUpdated,
                    'leftovers_area_cleared'        => (int) $clearedAreas,
                    'leftovers_reset_with_leftover' => (int) $resetWithLeftover,
                    'leftovers_deleted_new_rows'    => (int) $deletedWithoutLeftover,
                ],
                'timestamp' => $now->toIso8601String(),
            ];
        });
    }

    /**
     * @param object $r
     * @param array $dicts
     * @return array
     */
    public function transformLeftoverRowForItemView(object $r, array $dicts): array
    {
        [
            'goods'              => $goods,
            'packages'           => $packages,
            'users'              => $users,
            'realLoQtyById'      => $realLoQtyById,
            'muById'             => $muById,
            'packagesAllByGoods' => $packagesAllByGoods,
            'categoriesById'     => $categoriesById,
            'cellsById'          => $cellsById,
            'rowsById'           => $rowsById,
            'sectorsById'        => $sectorsById,
            'zonesById'          => $zonesById,
            'whById'             => $whById,
        ] = $dicts;

        $g = $goods[$r->goods_id] ?? null;
        $p = $packages[$r->package_id] ?? null;

        $cell = $r->cell_id && isset($cellsById[$r->cell_id]) ? (object) $cellsById[$r->cell_id] : null;
        $zoneName = $sectorName = $rowName = $warehouseName = $cellCode = null;

        if ($cell) {
            $cellCode = $cell->code ?? null;

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
        }

        $real       = !is_null($r->leftover_id);
        $hasCurrent = !is_null($r->current_leftovers);
        $current    = $hasCurrent ? (int) $r->current_leftovers : null;
        $erp        = $real ? (int) ($realLoQtyById[$r->leftover_id] ?? $r->quantity ?? 0) : null;

        $divergence = '-';

        if ($hasCurrent) {
            $divergence = (int) $current - (int) $erp;
        }

        $divergenceDisplay = $divergence === '-'
            ? '-'
            : ($divergence === 0 ? '0' : ($divergence > 0 ? '+'.$divergence : (string) $divergence));

        $who = $users[$r->item_update_id] ?? $users[$r->item_creator_id] ?? null;
        $ts  = $r->item_updated_at ?? null;

        $areaLabel    = $this->generateAreaLabel($r->area ?? null);
        $noCurrent    = !$hasCurrent;
        $hasName      = !$noCurrent && !empty($who);
        $hasTs        = !$noCurrent && !empty($ts);

        $respNameOut  = $hasName ? ($who . " ({$areaLabel})") : '-';
        $respDateOut  = $hasTs   ? Carbon::parse($ts)->format('Y.m.d') : '-';
        $respTimeOut  = $hasTs   ? Carbon::parse($ts)->format('H:i')    : '-';

        $muId = $g['measurement_unit_id'] ?? null;
        $measurementUnit = $muId ? ['id' => (string)$muId, 'name' => ($muById[$muId] ?? null)] : null;

        $packagesAll    = $packagesAllByGoods[$r->goods_id] ?? [];
        $packageCurrent = $r->package_id ? ([
            'id'   => (string)$r->package_id,
            'name' => ($packages[$r->package_id]['name'] ?? null),
            'qty'  => ($packages[$r->package_id]['main_units_number'] ?? null),
        ]) : null;

        return [
            'id'         => (string) $r->id,
            'local_id'   => (string) $r->id,
            'leftover_id'=> (string) $r->id,

            'real'       => $real ? 1 : 0,

            'name' => [
                'title'        => $g['name'] ?? null,
                'barcode'      => $real ?: $r->batch,
                'manufacturer' => $g['manufacturer'] ?? null,
                'category'     => isset($g['category_id']) ? ($categoriesById[$g['category_id']] ?? null) : null,
                'brand'        => $g['brand'] ?? null,
            ],

            'placing' => [
                'cell_id'   => $cell->id ?? null,
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
            'condition'         => '-',
            'package'           => $p['name'] ?? null,

            'current_leftovers' => $current,
            'leftovers_erp'     => $real ? $erp : $r->quantity,
            'divergence'        => $divergenceDisplay,

            'responsible_name'  => $respNameOut,
            'responsible_date'  => $respDateOut,
            'responsible_time'  => $respTimeOut,

            'measurement_unit' => $measurementUnit,
            'package_current'  => $packageCurrent,
            'packages_all'     => $packagesAll,
        ];
    }

    /**
     * @param $area
     * @return string|null
     */
    public function generateAreaLabel(?string $area): ?string
    {
        return in_array($area, ['web', 'api'], true)
            ? __("localization.inventory.area.$area")
            : null;
    }

    /**
     * @param Collection $cellIds
     * @return array
     */
    public function preloadCellHierarchy(Collection $cellIds): array
    {
        $cellsById = $rowsById = $sectorsById = $zonesById = $whById = [];

        if ($cellIds->isNotEmpty()) {
            $cells = DB::table('cells')
                ->whereIn('id', $cellIds)
                ->get(['id','code','parent_type','model_id'])
                ->keyBy('id');

            $cellsById = $cells->all();

            $rowIds    = $cells->where('parent_type', 'row')->pluck('model_id')
                ->unique()
                ->values();
            $sectorIds = $cells->where('parent_type', 'sector')->pluck('model_id')
                ->unique()
                ->values();
            $zoneIds   = $cells->where('parent_type', 'zone')->pluck('model_id')
                ->unique()
                ->values();

            if ($rowIds->isNotEmpty()) {
                $rowsById = DB::table('rows')->whereIn('id', $rowIds)->get(['id','name','sector_id'])
                    ->keyBy('id')
                    ->all();
                $sectorIds = $sectorIds->merge(collect($rowsById)->pluck('sector_id'))
                    ->filter()
                    ->unique()
                    ->values();
            }

            if ($sectorIds->isNotEmpty()) {
                $sectorsById = DB::table('sectors')
                    ->whereIn('id', $sectorIds)
                    ->get(['id','name','zone_id'])
                    ->keyBy('id')
                    ->all();
                $zoneIds = $zoneIds->merge(collect($sectorsById)->pluck('zone_id'))->filter()->unique()->values();
            }

            if ($zoneIds->isNotEmpty()) {
                $zones = DB::table('warehouse_zones')
                    ->whereIn('id', $zoneIds)
                    ->get(['id','name','warehouse_id'])
                    ->keyBy('id');
                $zonesById = $zones->all();
                $whIds = $zones->pluck('warehouse_id')->filter()->unique()->values();

                if ($whIds->isNotEmpty()) {
                    $whById = DB::table('warehouses')
                        ->whereIn('id', $whIds)
                        ->get(['id','name'])
                        ->keyBy('id')
                        ->all();
                }
            }
        }

        return [$cellsById, $rowsById, $sectorsById, $zonesById, $whById];
    }

    /**
     * @param string $inventoryItemId
     * @param string $containerId
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function getLeftoversByItemAndContainer(
        string $inventoryItemId,
        string $containerId,
        int $perPage = 50,
        int $page = 1
    ): array {
        $base = DB::table('inventory_leftovers as il')
            ->join('inventory_items as ii', 'ii.id', '=', 'il.inventory_item_id')
            ->where('il.inventory_item_id', $inventoryItemId)
            ->where('il.container_registers_id', $containerId)
            ->orderBy('il.created_at', 'desc');

        $total = (clone $base)->count();

        $rows = (clone $base)
            ->forPage($page, $perPage)
            ->get([
                'il.id as il_id',
                'il.leftover_id',
                'il.goods_id',
                'il.package_id',
                'il.area',
                'il.quantity',
                'il.current_leftovers',
                'il.batch',
                'il.manufacture_date',
                'il.bb_date',
                'il.source_type',
                'il.container_registers_id',
                'ii.id as item_id',
                'ii.cell_id',
                'ii.qty as item_qty',
                'ii.real_qty as item_real_qty',
                'ii.updated_at as item_updated_at',
                'ii.update_id as item_update_id',
                'ii.creator_id as item_creator_id',
                'ii.status as item_status',
            ]);

        $goods    = $this->mapById(
            'goods',
            ['id','name','manufacturer','brand','category_id','measurement_unit_id']
        );
        $packages = $this->mapById('packages', ['id','name','main_units_number']);
        $userIds  = $rows->pluck('item_update_id')
            ->merge($rows->pluck('item_creator_id'))
            ->filter()
            ->unique();
        $users    = $userIds->isNotEmpty()
            ? User::query()->whereIn('id', $userIds)
                ->get()
                ->mapWithKeys(fn($u) => [$u->id => $u->initial()])
                ->all()
            : [];

        $cellIds  = $rows->pluck('cell_id')->filter()->unique()->values();
        [$cellsById, $rowsById, $sectorsById, $zonesById, $whById] = $this->preloadCellHierarchy($cellIds);

        $leftoverIds = $rows->pluck('leftover_id')->filter()->unique()->values();
        $realLoQtyById = $leftoverIds->isNotEmpty()
            ? DB::table('leftovers')
                ->whereIn('id', $leftoverIds)
                ->pluck('quantity', 'id')
                ->all()
            : [];

        $goodsIds = $rows->pluck('goods_id')->filter()->unique()->values();
        $muIds = $goodsIds->map(fn($gid) => $goods[$gid]['measurement_unit_id'] ?? null)->filter()->unique();
        $muById = $muIds->isNotEmpty() ? MeasurementUnit::query()
            ->whereIn('id', $muIds)
            ->pluck('name','id')
            ->all() : [];

        $categoryIds = $goodsIds->map(fn($gid) => $goods[$gid]['category_id'] ?? null)->filter()->unique();
        $categoriesById = $categoryIds->isNotEmpty()
            ? DB::table('categories')->whereIn('id', $categoryIds)->pluck('name','id')->all()
            : [];

        $packagesAllByGoods = $goodsIds->isNotEmpty()
            ? DB::table('packages')
                ->whereIn('goods_id', $goodsIds)
                ->get(['id','name','goods_id','main_units_number'])
                ->groupBy('goods_id')
                ->map(fn($grp) => $grp->map(fn($p) => [
                    'id' => (string)$p->id,
                    'name' => $p->name,
                    'qty' => $p->main_units_number,
                ])->values()->all())
                ->all()
            : [];

        $data = $rows->values()->map(function ($il, int $k) use (
            $page, $perPage, $cellsById, $rowsById, $sectorsById, $zonesById, $whById,
            $goods, $packages, $users, $realLoQtyById, $muById, $packagesAllByGoods, $categoriesById
        ) {
            $g = $goods[$il->goods_id] ?? null;
            $p = $packages[$il->package_id] ?? null;

            $cell = $il->cell_id && isset($cellsById[$il->cell_id]) ? (object)$cellsById[$il->cell_id] : null;
            $zoneName = $sectorName = $rowName = $warehouseName = $cellCode = null;

            if ($cell) {
                $cellCode = $cell->code ?? null;

                if ($cell->parent_type === 'row') {
                    $row = $rowsById[$cell->model_id] ?? null;
                    $sector = $row ? ($sectorsById[$row->sector_id] ?? null) : null;
                    $zone = $sector ? ($zonesById[$sector->zone_id] ?? null) : null;
                    $wh = $zone ? ($whById[$zone->warehouse_id] ?? null) : null;
                    $rowName = $row->name ?? null;
                    $sectorName = $sector->name ?? null;
                    $zoneName = $zone->name ?? null;
                    $warehouseName = $wh->name ?? null;
                } elseif ($cell->parent_type === 'sector') {
                    $sector = $sectorsById[$cell->model_id] ?? null;
                    $zone = $sector ? ($zonesById[$sector->zone_id] ?? null) : null;
                    $wh = $zone ? ($whById[$zone->warehouse_id] ?? null) : null;
                    $sectorName = $sector->name ?? null;
                    $zoneName = $zone->name ?? null;
                    $warehouseName = $wh->name ?? null;
                } elseif ($cell->parent_type === 'zone') {
                    $zone = $zonesById[$cell->model_id] ?? null;
                    $wh = $zone ? ($whById[$zone->warehouse_id] ?? null) : null;
                    $zoneName = $zone->name ?? null;
                    $warehouseName = $wh->name ?? null;
                }
            }

            $real = !is_null($il->leftover_id);
            $hasCurrent = !is_null($il->current_leftovers);
            $current = $hasCurrent ? (int)$il->current_leftovers : null;
            $erp = $real ? (int)($realLoQtyById[$il->leftover_id] ?? $il->quantity ?? 0) : null;
            $divergence = $hasCurrent ? ((int)$current - (int)$erp) : '-';
            $divergenceDisplay = $divergence === '-'
                ? '-'
                : ($divergence === 0 ? '0' : ($divergence > 0 ? '+'.$divergence : (string)$divergence));

            $who = $users[$il->item_update_id] ?? $users[$il->item_creator_id] ?? null;
            $ts = $il->item_updated_at ?? null;
            $areaLabel = $this->generateAreaLabel($il->area);
            $respNameOut = $who ? ($who . " ({$areaLabel})") : '-';
            $respDateOut = $ts ? Carbon::parse($ts)->format('Y.m.d') : '-';
            $respTimeOut = $ts ? Carbon::parse($ts)->format('H:i') : '-';

            $muId = $g['measurement_unit_id'] ?? null;
            $measurementUnit = $muId ? ['id' => (string)$muId, 'name' => ($muById[$muId] ?? null)] : null;
            $packagesAll = $packagesAllByGoods[$il->goods_id] ?? [];
            $packageCurrent = $il->package_id ? [
                'id' => (string)$il->package_id,
                'name' => ($packages[$il->package_id]['name'] ?? null),
                'qty' => ($packages[$il->package_id]['main_units_number'] ?? null),
            ] : null;

            return [
                'id'               => (string)(($page - 1) * $perPage + $k + 1),
                'local_id'         => (string)$il->il_id,
                'leftover_id'      => (string)$il->il_id,
                'real'             => $real ? 1 : 0,
                'name' => [
                    'title'        => $g['name'] ?? null,
                    'barcode'      => '-',
                    'manufacturer' => $g['manufacturer'] ?? null,
                    'category'     => isset($g['category_id']) ? ($categoriesById[$g['category_id']] ?? null) : null,
                    'brand'        => $g['brand'] ?? null,
                ],
                'placing' => [
                    'cell_id'   => $cell->id ?? null,
                    'pallet'    => '',
                    'warehouse' => $warehouseName,
                    'zone'      => $zoneName,
                    'sector'    => $sectorName,
                    'row'       => $rowName,
                    'cell'      => $cellCode,
                    'code'      => '',
                ],
                'manufactured'      => $il->manufacture_date ? (string)$il->manufacture_date : null,
                'expiry'            => $il->bb_date ? (string)$il->bb_date : null,
                'party'             => $il->batch ? (string)$il->batch : null,
                'condition'         => '-',
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
        })->all();

        return [
            'total' => (int)$total,
            'data'  => $data,
        ];
    }

    /**
     * @param string $table
     * @param array $columns
     * @return array<string, array<string,mixed>>
     */
    public function mapById(string $table, array $columns = ['*']): array
    {
        return DB::table($table)->get($columns)->keyBy('id')->map(fn($r) => (array) $r)->all();
    }

    /**
     * @param Inventory $inventory
     * @return \Illuminate\Database\Query\Builder
     */
    protected function cellsQueryForInventory(Inventory $inventory): \Illuminate\Database\Query\Builder
    {
        $cells = DB::table('cells')
            ->select('cells.id', 'cells.code', 'cells.parent_type', 'cells.model_id');

        $isPartly = !empty($inventory->zone_id)
            || !empty($inventory->sector_id)
            || !empty($inventory->row_id)
            || !empty($inventory->cell_id);

        if ($isPartly) {
            if (!empty($inventory->cell_id)) {
                return $cells->where('cells.id', $inventory->cell_id);
            }

            if (!empty($inventory->row_id)) {
                return $cells->where('cells.parent_type', 'row')
                    ->where('cells.model_id', $inventory->row_id)
                    ->whereExists(function ($sq) use ($inventory) {
                        $sq->select(DB::raw(1))
                            ->from('rows')
                            ->whereColumn('rows.id', 'cells.model_id')
                            ->whereNull('rows.deleted_at');
                    });
            }

            if (!empty($inventory->sector_id)) {
                return $cells->where(function ($q) use ($inventory) {
                    $q->where(function ($q1) use ($inventory) {
                        $q1->where('cells.parent_type', 'sector')
                            ->where('cells.model_id', $inventory->sector_id)
                            ->whereExists(function ($sq) {
                                $sq->select(DB::raw(1))
                                    ->from('sectors')
                                    ->whereColumn('sectors.id', 'cells.model_id')
                                    ->whereNull('sectors.deleted_at');
                            });
                    })->orWhereExists(function ($sq) use ($inventory) {
                        $sq->select(DB::raw(1))
                            ->from('rows')
                            ->whereColumn('rows.id', 'cells.model_id')
                            ->where('cells.parent_type', 'row')
                            ->where('rows.sector_id', $inventory->sector_id)
                            ->whereNull('rows.deleted_at');
                    });
                });
            }

            if (!empty($inventory->zone_id)) {
                return $cells->where(function ($q) use ($inventory) {
                    $q->where(function ($q1) use ($inventory) {
                        $q1->where('cells.parent_type', 'zone')
                            ->where('cells.model_id', $inventory->zone_id)
                            ->whereExists(function ($sq) {
                                $sq->select(DB::raw(1))
                                    ->from('warehouse_zones')
                                    ->whereColumn('warehouse_zones.id', 'cells.model_id')
                                    ->whereNull('warehouse_zones.deleted_at');
                            });
                    })->orWhereExists(function ($sq) use ($inventory) {
                        $sq->select(DB::raw(1))
                            ->from('sectors')
                            ->whereColumn('sectors.id', 'cells.model_id')
                            ->where('cells.parent_type', 'sector')
                            ->where('sectors.zone_id', $inventory->zone_id)
                            ->whereNull('sectors.deleted_at');
                    })->orWhereExists(function ($sq) use ($inventory) {
                        $sq->select(DB::raw(1))
                            ->from('rows')
                            ->join('sectors', 'sectors.id', '=', 'rows.sector_id')
                            ->whereColumn('rows.id', 'cells.model_id')
                            ->where('cells.parent_type', 'row')
                            ->where('sectors.zone_id', $inventory->zone_id)
                            ->whereNull('rows.deleted_at')
                            ->whereNull('sectors.deleted_at');
                    });
                });
            }
        }

        return $cells->where(function ($q) use ($inventory) {
            $q->where(function ($q1) use ($inventory) {
                $q1->where('cells.parent_type', 'zone')
                    ->whereExists(function ($sq) use ($inventory) {
                        $sq->select(DB::raw(1))
                            ->from('warehouse_zones as wz')
                            ->whereColumn('wz.id', 'cells.model_id')
                            ->where('wz.warehouse_id', $inventory->warehouse_id)
                            ->whereNull('wz.deleted_at');
                    });
            })->orWhere(function ($q2) use ($inventory) {
                $q2->where('cells.parent_type', 'sector')
                    ->whereExists(function ($sq) use ($inventory) {
                        $sq->select(DB::raw(1))
                            ->from('sectors')
                            ->join('warehouse_zones as wz', 'wz.id', '=', 'sectors.zone_id')
                            ->whereColumn('sectors.id', 'cells.model_id')
                            ->where('wz.warehouse_id', $inventory->warehouse_id)
                            ->whereNull('sectors.deleted_at')
                            ->whereNull('wz.deleted_at');
                    });
            })->orWhere(function ($q3) use ($inventory) {
                $q3->where('cells.parent_type', 'row')
                    ->whereExists(function ($sq) use ($inventory) {
                        $sq->select(DB::raw(1))
                            ->from('rows')
                            ->join('sectors', 'sectors.id', '=', 'rows.sector_id')
                            ->join('warehouse_zones as wz', 'wz.id', '=', 'sectors.zone_id')
                            ->whereColumn('rows.id', 'cells.model_id')
                            ->where('wz.warehouse_id', $inventory->warehouse_id)
                            ->whereNull('rows.deleted_at')
                            ->whereNull('sectors.deleted_at')
                            ->whereNull('wz.deleted_at');
                    });
            });
        });
    }

    /**
     * @param Inventory $inventory
     * @return Builder
     */
    protected function leftoversQueryForInventory(Inventory $inventory): Builder
    {
        $q = Leftover::query()->from('leftovers');

        if (!empty($inventory->nomenclature_id)) {
            $q->where('leftovers.goods_id', $inventory->nomenclature_id);
        }

        if (!empty($inventory->cell_id)) {
            return $q->where('leftovers.cell_id', $inventory->cell_id);
        }

        if (!empty($inventory->row_id)) {
            return $q->join('cells', 'cells.id', '=', 'leftovers.cell_id')
                ->join('rows', 'rows.id', '=', 'cells.model_id')
                ->where('cells.parent_type', 'row')
                ->where('cells.model_id', $inventory->row_id)
                ->whereNull('cells.deleted_at')
                ->whereNull('rows.deleted_at')
                ->select('leftovers.*');
        }

        if (!empty($inventory->sector_id)) {
            return $q->join('cells', 'cells.id', '=', 'leftovers.cell_id')
                ->join('sectors', 'sectors.id', '=', 'cells.model_id')
                ->where('cells.parent_type', 'sector')
                ->where('cells.model_id', $inventory->sector_id)
                ->whereNull('cells.deleted_at')
                ->whereNull('sectors.deleted_at')
                ->select('leftovers.*');
        }

        if (!empty($inventory->zone_id)) {
            return $q->join('cells', 'cells.id', '=', 'leftovers.cell_id')
                ->join('warehouse_zones as wz', 'wz.id', '=', 'cells.model_id')
                ->where('cells.parent_type', 'zone')
                ->where('cells.model_id', $inventory->zone_id)
                ->whereNull('cells.deleted_at')
                ->whereNull('wz.deleted_at')
                ->select('leftovers.*');
        }

        return $q->join('cells', 'cells.id', '=', 'leftovers.cell_id')
            ->join('rows', function ($j) {
                $j->on('rows.id', '=', 'cells.model_id')
                    ->where('cells.parent_type','row')
                    ->whereNull('rows.deleted_at');
            })
            ->join('sectors', 'sectors.id', '=', 'rows.sector_id')
            ->join('warehouse_zones as wz', 'wz.id', '=', 'sectors.zone_id')
            ->where('wz.warehouse_id', $inventory->warehouse_id)
            ->whereNull('cells.deleted_at')
            ->whereNull('sectors.deleted_at')
            ->whereNull('wz.deleted_at')
            ->select('leftovers.*');
    }

    /**
     * @param int $qty
     * @param int|null $realQty
     * @return array
     */
    private function computeStatus(int $qty, ?int $realQty): array
    {
        if (!$realQty) {
            return [0, 'До інвентаризації'];
        }

        if ($realQty === $qty) {
            return [1, 'Без Розбіжностей'];
        }

        return [2, 'З Розбіжностями'];
    }
}
