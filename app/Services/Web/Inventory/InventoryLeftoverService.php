<?php

namespace App\Services\Web\Inventory;

use App\Models\Entities\Inventory\InventoryItem;
use App\Models\Entities\Inventory\InventoryLeftover;
use App\Models\Entities\Inventory\InventoryManualLeftoverLog;
use App\Models\Entities\Leftover\Leftover;
use App\Services\Terminal\Inventory\InventoryLeftoverServiceInterface;
use App\Services\Web\Leftover\LeftoverServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

/**
 * InventoryLeftoverService.
 */
class InventoryLeftoverService implements InventoryLeftoverServiceInterface
{
    protected ?string $logGroupId = null;
    protected ?string $logGroupType = 'manual_sync';

    /**
     * @param LeftoverServiceInterface $LeftoverService
     */
    public function __construct(
        protected LeftoverServiceInterface $LeftoverService
    ) {}

    /**
     * @param string $inventoryItemId
     * @param array $data
     * @return InventoryLeftover
     * @throws Throwable
     */
    public function createForItem(string $inventoryItemId, array $data): InventoryLeftover
    {
        /** @var InventoryItem $item */
        $item = InventoryItem::query()->findOrFail($inventoryItemId);

        $creatorId = Auth::id() ?? $item->creator_id;

        $payload = [
            'inventory_item_id'      => $item->id,
            'leftover_id'            => Arr::get($data, 'leftover_id'),
            'goods_id'               => Arr::get($data, 'goods_id'),
            'package_id'             => Arr::get($data, 'packages_id'),
            'current_leftovers'      => (int) Arr::get($data, 'quantity', 0),
            'quantity'               => 0,
            'batch'                  => (string) Arr::get($data, 'batch', ''),
            'manufacture_date'       => Arr::get($data, 'manufacture_date'),
            'bb_date'                => Arr::get($data, 'bb_date'),
            'source_type'            => Arr::get($data, 'leftover_id')
                ? InventoryLeftover::SOURCE_EXISTING
                : InventoryLeftover::SOURCE_NEW,
            'creator_id'             => $creatorId,
            'container_registers_id' => Arr::get($data, 'container_registers_id'),
            'expiration_term'        => Arr::get($data, 'expiration_term'),
            'condition'              => (bool) Arr::get($data, 'condition'),
        ];

        return DB::transaction(function () use ($payload) {
            /** @var InventoryLeftover|null $result */
            $result = null;

            InventoryLeftover::withoutTouching(function () use (&$result, $payload) {
                /** @var InventoryLeftover $il */
                $il = InventoryLeftover::query()->create($payload);

                $result = $il->refresh();
            });

            return $result;
        });
    }

    /**
     * @param string $leftoverId
     * @param array $data
     * @return InventoryLeftover
     */
    public function update(string $leftoverId, array $data): InventoryLeftover
    {
        /** @var InventoryLeftover $il */
        $il = InventoryLeftover::query()->findOrFail($leftoverId);

        $payload = Arr::only($data, [
            'goods_id',
            'package_id',
            'quantity',
            'batch',
            'manufacture_date',
            'bb_date',
            'expiration_term',
            'container_registers_id',
            'current_leftovers',
            'leftover_id',
        ]);

        $il->fill($payload);
        $il->save();

        return $il->refresh();
    }

    /**
     * @param string $inventoryLeftoverId
     * @param float|int $quantity
     * @param string|null $packageId
     * @return array
     * @throws Throwable
     */
    public function correctCurrent(string $inventoryLeftoverId, float|int $quantity, ?string $packageId = null): array
    {
        return DB::transaction(function () use ($packageId, $inventoryLeftoverId, $quantity) {
            /** @var InventoryLeftover $il */
            $il = InventoryLeftover::query()
                ->lockForUpdate()
                ->findOrFail($inventoryLeftoverId);

            $il->current_leftovers = (int) $quantity;

            if ($packageId !== null) {
                $il->package_id = $packageId;
            }

            $il->save();

            $erp = $il->leftover_id
                ? (int) (DB::table('leftovers')
                    ->where('id', $il->leftover_id)
                    ->value('quantity') ?? 0)
                : (int) ($il->quantity ?? 0);

            $current = (int) ($il->current_leftovers ?? 0);
            $diff    = $current - $erp;
            $div     = $diff === 0 ? '0' : ($diff > 0 ? ('+' . $diff) : (string) $diff);

            return [
                'status'            => 'ok',
                'item_id'           => (string) $il->inventory_item_id,
                'current_leftovers' => $current,
                'leftovers_erp'     => $erp,
                'divergence'        => $div,
            ];
        });
    }

    /**
     * @param string $inventoryLeftoverId
     * @return array
     */
    public function findOne(string $inventoryLeftoverId): array
    {
        /** @var InventoryLeftover $il */
        $il = InventoryLeftover::query()->findOrFail($inventoryLeftoverId);

        $erp = $il->leftover_id
            ? (int) (DB::table('leftovers')->where('id', $il->leftover_id)->value('quantity') ?? 0)
            : (int) ($il->quantity ?? 0);

        $current = (int) ($il->current_leftovers ?? 0);
        $diff    = $current - $erp;
        $div     = $diff === 0 ? '0' : ($diff > 0 ? ('+' . $diff) : (string) $diff);

        return [
            'id'                 => (string) $il->id,
            'inventory_item_id'  => (string) $il->inventory_item_id,
            'leftover_id'        => $il->leftover_id ? (string) $il->leftover_id : null,
            'goods_id'           => (string) $il->goods_id,
            'package_id'         => (string) $il->package_id,
            'quantity'           => (int) $il->quantity,
            'current_leftovers'  => $current,
            'batch'              => (string) $il->batch,
            'manufacture_date'   => $il->manufacture_date?->toDateString(),
            'bb_date'            => $il->bb_date?->toDateString(),
            'source_type'        => (int) $il->source_type,
            'real'               => $il->leftover_id ? 1 : 0,
            'leftovers_erp'      => $erp,
            'divergence'         => $div,
            'creator_id'         => (string) $il->creator_id,
            'approved_at'        => $il->approved_at?->toDateTimeString(),
            'created_at'         => $il->created_at?->toDateTimeString(),
            'updated_at'         => $il->updated_at?->toDateTimeString(),
            'expiration_term'    => $il->expiration_term,
            'container_registers_id' => $il->container_registers_id
        ];
    }

    /**
     * @param string $inventoryLeftoverId
     * @return void
     */
    public function deleteOne(string $inventoryLeftoverId): void
    {
        /** @var InventoryLeftover $il */
        $il = InventoryLeftover::query()->findOrFail($inventoryLeftoverId);
        $il->delete();
    }

    /**
     * @param array $inventoryLeftovers
     * @return array
     * @throws Throwable
     */
    public function leftoversSync(array $inventoryLeftovers): array
    {
        $createdCount = 0;
        $updatedCount = 0;

        $this->logGroupId   = (string) Str::uuid();
        $this->logGroupType = 'manual_sync';

        DB::transaction(function () use ($inventoryLeftovers, &$createdCount, &$updatedCount) {
            $createItems = $this->LeftoverService
                ->enrichWithWarehouseIds(collect($inventoryLeftovers['create'] ?? []));

            $createItems->each(function ($item) use (&$createdCount) {
                $this->createLeftover($item);
                $createdCount++;
            });

            foreach ($inventoryLeftovers['update'] ?? [] as $updateItem) {
                $this->updateLeftover($updateItem);
                $updatedCount++;
            }
        });

        $this->logGroupId = null;
        $this->logGroupType = null;

        return ['created_count' => $createdCount, 'updated_count' => $updatedCount];
    }

    /**
     * @param array $data
     * @return void
     */
    protected function createLeftover(array $data): void
    {
        $leftover = Leftover::create($data);

        InventoryManualLeftoverLog::recordChange(
            leftoverId: (string) $leftover->id,
            qtyBefore:  '0',
            qtyAfter:   isset($leftover->quantity) ? (string) $leftover->quantity : null,
            area:       $leftover->area ?? null,
            executorId: optional(Auth::user())->id,
            at:         null,
            groupId:    $this->logGroupId,
            groupType:  $this->logGroupType
        );
    }

    /**
     * Updates an existing Leftover record.
     * @throws ModelNotFoundException
     */
    protected function updateLeftover(array $data): void
    {
        if (empty($data['leftover_id'])) {
            return;
        }

        $leftoverId = (string) $data['leftover_id'];
        unset($data['leftover_id']);

        /** @var Leftover $model */
        $model = Leftover::query()->findOrFail($leftoverId);
        $qtyBefore  = $model->quantity !== null ? (string) $model->quantity : null;
        $areaBefore = $model->area ?? null;

        $hasQty = array_key_exists('quantity', $data);
        $newQty = $hasQty ? (float) $data['quantity'] : null;

        if ($hasQty && $newQty !== null && $newQty <= 0) {
            $deleted = $model->forceDelete();

            if ($deleted === 0) {
                \Log::warning("Leftover {$leftoverId} not found or already deleted during updateLeftover().");
            } else {
                InventoryManualLeftoverLog::recordChange(
                    leftoverId: $leftoverId,
                    qtyBefore:  $qtyBefore,
                    qtyAfter:   '0.000',
                    area:       $areaBefore,
                    executorId: optional(Auth::user())->id,
                    at:         null,
                    groupId:    $this->logGroupId,
                    groupType:  $this->logGroupType
                );
            }

            return;
        }

        $updated = Leftover::whereKey($leftoverId)->update($data);

        if ($updated === 0) {
            \Log::warning("Leftover {$leftoverId} not updated — record not found or identical data.");

            return;
        }

        $model->refresh();
        $qtyAfter  = $model->quantity !== null ? (string) $model->quantity : null;
        $areaAfter = $model->area ?? null;

        InventoryManualLeftoverLog::recordChange(
            leftoverId: $leftoverId,
            qtyBefore:  $qtyBefore,
            qtyAfter:   $qtyAfter,
            area:       $areaAfter,
            executorId: optional(Auth::user())->id,
            at:         null,
            groupId:    $this->logGroupId,
            groupType:  $this->logGroupType
        );
    }

    /**
     * @param array $items
     * @param string $inventoryItemId
     * @return array[]
     * @throws Throwable
     */
    public function syncByInventoryItem(array $items, string $inventoryItemId): array
    {
        $created = [];
        $updated = [];

        DB::transaction(function () use ($items, $inventoryItemId, &$created, &$updated) {
            foreach ($items as $item) {
                if (!empty($item['id'])) {
                    $id   = (string) $item['id'];
                    $data = Arr::except($item, ['id']);

                    if (Arr::has($data, 'packages_id') && !Arr::has($data, 'package_id')) {
                        $data['package_id'] = $data['packages_id'];
                    }

                    if (Arr::has($data, 'quantity') && !Arr::has($data, 'current_leftovers')) {
                        $data['current_leftovers'] = (int) $data['quantity'];
                    }

                    $model = $this->update($id, $data);
                    $updated[] = (string) $model->id;
                    continue;
                }

                if (Arr::has($item, 'quantity') && !Arr::has($item, 'current_leftovers')) {
                    $item['current_leftovers'] = (int) $item['quantity'];
                }

                $model = $this->createForItem($inventoryItemId, $item);
                $created[] = (string) $model->id;
            }

            DB::table('inventory_items')
                ->where('id', $inventoryItemId)
                ->update([
                    'status'     => 2,
                    'updated_at' => now(),
                ]);
        });

        return ['created_ids' => $created, 'updated_ids' => $updated];
    }
}
