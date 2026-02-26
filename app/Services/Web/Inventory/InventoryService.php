<?php

namespace App\Services\Web\Inventory;

use App\Enums\Cells\CellStatus;
use App\Models\Dictionaries\MeasurementUnit;
use App\Models\Entities\Inventory\Inventory;
use App\Models\User;
use App\Services\Terminal\Inventory\InventoryItemServiceInterface as ItemService;
use App\Services\Terminal\Inventory\InventoryServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Tables\Inventory\TableFacade as InventoryTableFacade;

/**
 * Inventory.
 */
class InventoryService implements InventoryServiceInterface
{
    /**
     * @param ItemService $itemService
     */
    public function __construct(
        private readonly ItemService $itemService,
    ) {}

    /**
     * @param array $data
     * @return array
     * @throws \Throwable
     */
    public function store(array $data): array
    {
        $payload = $this->normalize($data);
        $payload['type'] = $this->detectType($payload);

        $performerIds = Arr::wrap($data['performer_type'] ?? []);
        $leadId = $performerIds[0] ?? null;
        $payload['performer_id'] = $leadId ?: null;
        $nomenclatureIds = Arr::wrap($data['nomenclature'] ?? []);

        return DB::transaction(function () use ($payload, $performerIds, $nomenclatureIds) {
            /** @var Inventory $inv */
            $inv = Inventory::create($payload);
            $this->syncPerformers($inv, $performerIds);
            if (in_array('all', $nomenclatureIds, true)) {
                $inv->goods()->attach([null]);
            } elseif (!empty($nomenclatureIds)) {
                $inv->goods()->sync($nomenclatureIds);
            }

            $generated = $this->itemService->seedFromInventory($inv);

            return [$inv, (int) $generated];
        });
    }

    /**
     * @param Inventory $inventory
     * @param array $ids
     * @return void
     */
    private function syncPerformers(Inventory $inventory, array $ids): void
    {
        $ids = collect($ids)
            ->filter(fn ($v) => is_string($v) && Str::isUuid($v))
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            $inventory->performers()->sync([]);

            return;
        }

        $validUserIds = User::query()
            ->whereIn('id', $ids)
            ->pluck('id')
            ->all();

        $inventory->performers()->sync($validUserIds);
    }

    /**
     * @param Inventory $inventory
     * @param array $data
     * @return Inventory
     * @throws \Throwable
     */
    public function update(Inventory $inventory, array $data): Inventory
    {
        $payload = $this->normalize($data);
        unset($payload['creator_id'], $payload['performer_id']);

        $typeKeys = [
            'zone_id',
            'sector_id',
            'row_id',
            'cell_id',
            'category_subcategory',
            'manufacturer_id',
            'supplier_id',
        ];

        $shouldRecalcType = array_key_exists('type', $data)
            || count(array_intersect(array_keys($payload), $typeKeys)) > 0;

        if ($shouldRecalcType) {
            $baseScope       = Arr::only($inventory->getAttributes(), $typeKeys);
            $payload['type'] = $this->detectType($baseScope + $payload);
        }

        $hasPerformerData = array_key_exists('performer_type', $data);
        $performerIds     = $hasPerformerData
            ? Arr::wrap($data['performer_type'])
            : null;

        $hasNomenclature  = array_key_exists('nomenclature', $data);
        $nomenclatureIds  = $hasNomenclature
            ? Arr::wrap($data['nomenclature'])
            : [];

        return DB::transaction(function () use ($inventory, $payload, $hasPerformerData, $performerIds, $hasNomenclature, $nomenclatureIds) {
            $inventory->fill($payload)->save();

            if ($hasPerformerData) {
                $this->syncPerformers($inventory, $performerIds);
            }

            if ($hasNomenclature) {
                $inventory->goods()->detach();

                if (in_array('all', $nomenclatureIds, true)) {
                    $inventory->goods()->attach([null]);

                } elseif (!empty($nomenclatureIds)) {
                    $inventory->goods()->sync($nomenclatureIds);
                }
            }

            return $inventory;
        });
    }

    /**
     * @param Inventory $inventory
     * @return void
     */
    public function delete(Inventory $inventory): void
    {
        $inventory->delete();
    }

    /**
     * @param string $id
     * @param array $with
     * @return Inventory
     */
    public function get(string $id, array $with = []): Inventory
    {
        $with = $this->sanitizeWith($with);

        return Inventory::with($with)->findOrFail($id);
    }

    /**
     * @param array $filters
     * @param int $perPage
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], int $perPage = 15, ?int $page = null): LengthAwarePaginator
    {
        $q = Inventory::query();

        $with = $this->sanitizeWith(Arr::get($filters, 'with', []));

        if ($with) {
            $q->with($with);
        }

        $this->applyFilters($q, $filters);
        $table = $q->getModel()->getTable();
        $q->orderByRaw("CASE WHEN {$table}.status = 2 THEN 0 ELSE 1 END");

        [$sort, $dir] = $this->parseSort($filters);
        $q->orderBy($sort, $dir);

        $perPage = max(1, min(200, $perPage));
        $page    = $page ?? (int) request()->input('page', 1);

        return $q->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * @param array $filters
     * @param int|null $limit
     * @return EloquentCollection
     */
    public function getAll(array $filters = [], ?int $limit = null): EloquentCollection
    {
        $q = Inventory::query();

        $with = $this->sanitizeWith(Arr::get($filters, 'with', []));

        if ($with) {
            $q->with($with);
        }

        $this->applyFilters($q, $filters);

        [$sort, $dir] = $this->parseSort($filters);
        $q->orderBy($sort, $dir);

        if ($limit !== null) {
            $q->limit(max(1, $limit));
        }

        return $q->get();
    }

    /**
     * @param string $inventoryItemId
     * @return array
     */
    public function getContainersWithLeftoversByItem(string $inventoryItemId): array
    {
        $containers = DB::table('container_registers as cr')
            ->whereExists(function ($sq) use ($inventoryItemId) {
                $sq->select(DB::raw(1))
                    ->from('inventory_leftovers as il')
                    ->whereNotNull('il.container_registers_id')
                    ->where('il.inventory_item_id', $inventoryItemId)
                    ->whereColumn('il.container_registers_id', 'cr.id');
            })
            ->select('cr.*')
            ->orderByDesc('cr.created_at')
            ->get();

        if ($containers->isEmpty()) {
            return ['total' => 0, 'data' => []];
        }

        $containerIds = $containers->pluck('id')->all();

        $ilRows = DB::table('inventory_leftovers as il')
            ->join('inventory_items as ii', 'ii.id', '=', 'il.inventory_item_id')
            ->where('il.inventory_item_id', $inventoryItemId)
            ->whereIn('il.container_registers_id', $containerIds)
            ->orderBy('il.created_at', 'desc')
            ->get([
                'il.*',
                'ii.id as item_id',
                'ii.cell_id',
                'ii.qty as item_qty',
                'ii.real_qty as item_real_qty',
                'ii.updated_at as item_updated_at',
                'ii.update_id as item_update_id',
                'ii.creator_id as item_creator_id',
                'ii.status as item_status',
            ])
            ->groupBy('container_registers_id');

        $leftoverIds = $ilRows->flatten()->pluck('leftover_id')->filter()->unique()->values();
        $realLoQtyById = $leftoverIds->isNotEmpty()
            ? DB::table('leftovers')
                ->whereIn('id', $leftoverIds)
                ->pluck('quantity', 'id')
                ->all()
            : [];

        $goodsIds = $ilRows->flatten()->pluck('goods_id')->filter()->unique()->values();
        $goods    = $goodsIds->isNotEmpty()
            ? $this->itemService->mapById(
                'goods',
                ['id','name','manufacturer','brand','category_id','measurement_unit_id']
            ) : [];

        $packageIds = $ilRows->flatten()->pluck('package_id')->filter()->unique()->values();
        $packages   = $packageIds->isNotEmpty()
            ? $this->itemService->mapById('packages', ['id','name','main_units_number'])
            : [];

        $userIds = $ilRows->flatten()
            ->pluck('item_update_id')->merge($ilRows->flatten()->pluck('item_creator_id'))
            ->filter()->unique()->values();
        $users = $userIds->isNotEmpty()
            ? User::query()
                ->whereIn('id', $userIds)
                ->get()
                ->mapWithKeys(fn($u) => [$u->id => $u->initial()])->all()
            : [];

        $cellIds = $ilRows->flatten()->pluck('cell_id')->filter()->unique()->values();
        [$cellsById, $rowsById, $sectorsById, $zonesById, $whById] = $this->itemService->preloadCellHierarchy($cellIds);

        $muIds = $goodsIds->map(fn($gid) => $goods[$gid]['measurement_unit_id'] ?? null)->filter()->unique()->values();
        $muById = $muIds->isNotEmpty()
            ? MeasurementUnit::query()->whereIn('id', $muIds)->pluck('name', 'id')->all()
            : [];

        $categoryIds = $goodsIds->map(fn($gid) => $goods[$gid]['category_id'] ?? null)->filter()->unique()->values();
        $categoriesById = $categoryIds->isNotEmpty()
            ? DB::table('categories')->whereIn('id', $categoryIds)->pluck('name', 'id')->all()
            : [];

        $packagesAllByGoods = $goodsIds->isNotEmpty()
            ? DB::table('packages')
                ->whereIn('goods_id', $goodsIds)
                ->get(['id','name','goods_id','main_units_number'])
                ->groupBy('goods_id')->map(function ($grp) {
                    return $grp->map(fn($p) => ['id'=>(string)$p->id,'name'=>$p->name,'qty'=>$p->main_units_number])
                        ->values()
                        ->all();
                })->all()
            : [];

        $dicts = [
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
        ];

        $data = $containers->map(function ($cr) use ($ilRows, $dicts) {
            $rows = $ilRows[$cr->id] ?? collect();

            $leftovers = $rows->values()->map(
                fn ($r) => $this->itemService->transformLeftoverRowForItemView($r, $dicts)
            )->all();

            return [
                'id'               => (string) $cr->id,
                'uuid'             => (string) $cr->id,
                'created_at'       => $cr->created_at ?? null,
                'updated_at'       => $cr->updated_at ?? null,
                'leftovers_count'  => count($leftovers),
                'leftovers'        => $leftovers,
            ];
        })->values()->all();

        return [
            'total' => count($data),
            'data'  => $data,
        ];
    }

    /**
     * @param array $p
     * @return string
     */
    private function detectType(array $p): string
    {
        $partlyKeys = [
            'zone_id','sector_id','row_id','cell_id',
            'category_subcategory','manufacturer_id','supplier_id','nomenclature_id',
        ];

        foreach ($partlyKeys as $k) {
            if (!empty($p[$k])) {
                return 'partly';
            }
        }

        return 'full';
    }

    /**
     * @param array $data
     * @return array
     */
    private function normalize(array $data): array
    {
        return [
            'show_leftovers'           => (bool) Arr::get($data, 'show_leftovers', false),
            'restrict_goods_movement'  => (bool) Arr::get($data, 'restrict_goods_movement', false),
            'process_cell'             => (int) Arr::get($data, 'process_cell', 0),
            'creator_id'               => Auth::id() ?: null,
            'performer_id'             => Arr::get($data, 'performer_type') ?: null,
            'warehouse_id'             => Arr::get($data, 'warehouse'),
            'warehouse_erp_id'         => Arr::get($data, 'warehouse_erp') ?: null,
            'zone_id'                  => Arr::get($data, 'zone') ?: null,
            'sector_id'                => Arr::get($data, 'sector') ?: null,
            'row_id'                   => Arr::get($data, 'row') ?: null,
            'cell_id'                  => Arr::has($data, 'cell') && $data['cell'] !== '' ? $data['cell'] : null,
            'category_subcategory'     => Arr::has($data, 'category_subcategory') && $data['category_subcategory'] !== '' ? $data['category_subcategory'] : null,
            'manufacturer_id'          => Arr::get($data, 'manufacturer') ?: null,
            'supplier_id'              => Arr::get($data, 'supplier') ?: null,
            'start_date'               => Arr::get($data, 'start_date') ?: null,
            'end_date'                 => Arr::get($data, 'end_date') ?: null,
            'comment'                  => Arr::get($data, 'comment') ?: null,
            'priority'                 => Arr::get($data, 'priority') ?: 0,
            'brand'                    => Arr::get($data, 'brand') ?: null,
        ];
    }

    /**
     * @param Builder $q
     * @param array $f
     * @return void
     */
    private function applyFilters(Builder $q, array $f): void
    {
        foreach ([
                     'type','creator_id','warehouse_id','warehouse_erp_id','performer_id',
                     'zone_id','sector_id','row_id','cell_id',
                     'category_subcategory','manufacturer_id','supplier_id','nomenclature_id',
                     'status',
                 ] as $k) {
            if (isset($f[$k]) && $f[$k] !== '' && $f[$k] !== null) {
                $q->where($k, $f[$k]);
            }
        }

        foreach (['show_leftovers','restrict_goods_movement'] as $b) {
            if (array_key_exists($b, $f) && $f[$b] !== null && $f[$b] !== '') {
                $q->where($b, (bool) $f[$b]);
            }
        }

        if (isset($f['process_cell']) && $f['process_cell'] !== '' && $f['process_cell'] !== null) {
            $q->where('process_cell', (int) $f['process_cell']);
        }

        if (!empty($f['start_from'])) $q->where('start_date', '>=', $f['start_from']);
        if (!empty($f['start_to']))   $q->where('start_date', '<=', $f['start_to']);
        if (!empty($f['end_from']))   $q->where('end_date', '>=', $f['end_from']);
        if (!empty($f['end_to']))     $q->where('end_date', '<=', $f['end_to']);

        if (!empty($f['search'])) {
            $term = '%'.str_replace('%', '\%', $f['search']).'%';
            $q->where('comment', 'ilike', $term);
        }

        if (!empty($f['ids']) && is_array($f['ids'])) {
            $q->whereIn('id', $f['ids']);
        }

        if (isset($f['status_not']) && $f['status_not'] !== '' && $f['status_not'] !== null) {
            $statuses = Arr::wrap($f['status_not']);

            if (count($statuses) > 1) {
                $q->whereNotIn('inventories.status', $statuses);
            } else {
                $q->where('inventories.status', '<>', $statuses[0]);
            }
        }

        if (isset($f['item_status_not']) && $f['item_status_not'] !== '' && $f['item_status_not'] !== null) {
            $statuses = Arr::wrap($f['item_status_not']);
            $q->whereNotExists(function ($sq) use ($statuses) {
                $sq->select(DB::raw(1))
                    ->from('inventory_items as ii')
                    ->whereColumn('ii.inventory_id', 'inventories.id')
                    ->whereIn('ii.status', $statuses);
            });
        }
    }

    /**
     * @param array $filters
     * @return array
     */
    private function parseSort(array $filters): array
    {
        $allowed = ['created_at','start_date','end_date','type'];
        $sort = in_array(($filters['sort'] ?? ''), $allowed, true) ? $filters['sort'] : 'created_at';

        $dir = strtolower((string) ($filters['sort_dir'] ?? 'desc'));
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        return [$sort, $dir];
    }

    /**
     * @param array $with
     * @return array
     */
    private function sanitizeWith(array $with): array
    {
        if (!$with) {
            return [];
        }

        $allowed = [
            'user','warehouse','warehouseErp','performer',
            'zone','sector','row','cell',
            'category','manufacturer','supplier','nomenclature',
        ];

        return array_values(array_intersect($allowed, $with));
    }

    /**
     * @param Request $request
     * @return array
     */

    public function gridTableDataFromRequest(Request $request): array
    {
        $this->setLocale($request);

        return InventoryTableFacade::getFilteredData(\Auth::id());
    }

    /**
     * @param string $id
     * @param string $action
     * @return array
     * @throws \Throwable
     */
    public function transition(string $id, string $action): array
    {
        $map = [
            'proceed'       => ['status' => Inventory::STATUS_PENDING],
            'pause'         => ['status' => Inventory::STATUS_PAUSED],
            'finish'        => ['status' => Inventory::STATUS_FINISHED,        'set_end' => true],
            'finish_before' => ['status' => Inventory::STATUS_FINISHED_BEFORE, 'set_end' => true],
        ];

        if (!isset($map[$action])) {
            return [
                'inventory' => $this->get($id),
                'flash'     => ['key' => 'error', 'msg' => 'Unknown transition.'],
            ];
        }

        $inventory    = $this->get($id);
        $targetStatus = (int) $map[$action]['status'];

        if ((int) ($inventory->status ?? 0) === $targetStatus) {
            return [
                'inventory' => $inventory,
                'flash'     => ['key' => 'info', 'msg' => "Inventory already {$action}."],
            ];
        }

        DB::transaction(function () use ($inventory, $map, $action, $targetStatus) {
            $payload = [
                'status' => $targetStatus,
            ];

            if (!empty($map[$action]['set_start']) && empty($inventory->start_date)) {
                $payload['start_date'] = now();
            }

            if (!empty($map[$action]['set_end'])) {
                $payload['end_date'] = now();
            }

            $inventory->fill($payload);
            $inventory->save();
            $inventory->refresh();

            if ($inventory->restrict_goods_movement && in_array($targetStatus, [Inventory::STATUS_IN_PROGRESS, Inventory::STATUS_PENDING])) {
                $this->setRealLeftoversStatusForInventory($inventory, 3);

                $cellIds = DB::table('inventory_items')
                    ->where('inventory_id', $inventory->id)
                    ->pluck('cell_id')
                    ->all();

                if (!empty($cellIds)) {
                    DB::table('cells')
                        ->whereIn('id', $cellIds)
                        ->whereNull('deleted_at')
                        ->update([
                            'status'     => CellStatus::BLOCKED->value,
                        ]);
                }
            }

            if ($inventory->restrict_goods_movement && $targetStatus === Inventory::STATUS_FINISHED) {
                $this->setRealLeftoversStatusForInventory($inventory, 1);
                $this->itemService->applyRealQtyToLeftoversByInventory($inventory);
                $this->itemService->recalculateContainerStatusesForInventory($inventory);

                $cellIds = DB::table('inventory_items')
                    ->where('inventory_id', $inventory->id)
                    ->pluck('cell_id')
                    ->all();

                if (!empty($cellIds)) {
                    DB::table('cells')
                        ->whereIn('id', $cellIds)
                        ->whereNull('deleted_at')
                        ->update([
                            'status'     => CellStatus::ACTIVE->value,
                        ]);
                }
            }
        });

        $msg = "Inventory {$action} successful.";

        return [
            'inventory' => $inventory->refresh(),
            'flash'     => ['key' => 'success', 'msg' => $msg],
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function indexDataFromRequest(Request $request): array
    {
        $filters = $request->only([
            'type','user_id','warehouse_id','warehouse_erp_id',
            'zone_id','sector_id','row_id','cell_id',
            'category_subcategory','manufacturer_id','supplier_id','nomenclature_id',
            'show_leftovers','restrict_goods_movement','process_cell',
            'start_from','start_to','end_from','end_to',
            'search','with','sort','sort_dir',
        ]);

        if (!array_key_exists('warehouse_id', $filters)
            || $filters['warehouse_id'] === ''
            || $filters['warehouse_id'] === null) {
            $currentWarehouseId = Auth::user()?->getCurrentWarehouseIdAttribute();

            if ($currentWarehouseId) {
                $filters['warehouse_id'] = $currentWarehouseId;
            }
        }

        $perPage = (int) $request->input('per_page', 15);
        $page    = (int) $request->input('page', 1);

        $filters['creator_id'] = Auth::user()?->id;

        $filters['status_not'] = [
            Inventory::STATUS_CREATED,
            Inventory::STATUS_FINISHED,
            Inventory::STATUS_FINISHED_BEFORE,
        ];

        $inventories = $this->getList($filters, $perPage, $page);

        $attachItemsData = function ($inv) {
            if ($inv && method_exists($inv, 'getItemsData')) {
                $inv->items_data = $inv->getItemsData();
            }

            return $inv;
        };

        if ($inventories instanceof \Illuminate\Pagination\LengthAwarePaginator
            || $inventories instanceof \Illuminate\Pagination\Paginator) {
            $inventories->setCollection(
                $inventories->getCollection()->map($attachItemsData)
            );
        } elseif ($inventories instanceof \Illuminate\Support\Collection) {
            $inventories = $inventories->map($attachItemsData);
        } else {
            $inventories = $attachItemsData($inventories);
        }

        $inventoryCount = Inventory::count();

        return [
            'inventories'    => $inventories,
            'inventoryCount' => $inventoryCount,
        ];
    }

    /**
     * @param $request
     * @return void
     */
    protected function setLocale($request): void
    {
        app()->setLocale(
            $request->get('lang')
            ?? session('locale')
            ?? auth()->user()->locale
            ?? config('app.locale')
        );
    }

    /**
     * @param Inventory $inventory
     * @return array
     */
    private function getRealLeftoverIdsForInventory(Inventory $inventory): array
    {
        return DB::table('inventory_leftovers as il')
            ->join('inventory_items as ii', 'ii.id', '=', 'il.inventory_item_id')
            ->where('ii.inventory_id', $inventory->id)
            ->whereNotNull('il.leftover_id')
            ->pluck('il.leftover_id')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Set status for those real leftovers tied to the given inventory.
     *
     * @param Inventory $inventory
     * @param int $status
     * @return void
     */
    private function setRealLeftoversStatusForInventory(Inventory $inventory, int $status): void
    {
        $ids = $this->getRealLeftoverIdsForInventory($inventory);

        if (empty($ids)) {
            return;
        }

        DB::table('leftovers')
            ->whereIn('id', $ids)
            ->update([
                'status_id'     => $status,
                'updated_at' => now(),
            ]);
    }
}
