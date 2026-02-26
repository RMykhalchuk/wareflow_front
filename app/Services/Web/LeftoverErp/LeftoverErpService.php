<?php

namespace App\Services\Web\LeftoverErp;

use App\Models\Entities\LeftoverErp\LeftoverErp;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Services\Web\Auth\AuthContextService;

class LeftoverErpService implements LeftoverErpServiceInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return LeftoverErp::orderByDesc('created_at')->paginate($perPage);
    }

    public function find(string $id): LeftoverErp
    {
        return LeftoverErp::findOrFail($id);
    }

    public function store(array $data): LeftoverErp
    {
        return LeftoverErp::create($data);
    }

    public function update(LeftoverErp $leftoverErp, array $data): LeftoverErp
    {
        $leftoverErp->update($data);
        return $leftoverErp->refresh();
    }

    public function delete(LeftoverErp $leftoverErp): void
    {
        $leftoverErp->delete();
    }

    public function bulkUpsert(array $rows): array
    {
        // Normalize and deduplicate by unique key: goods_erp_id + batch + warehouse_erp_id
        $deduped = [];
        foreach ($rows as $row) {
            $batch = $row['batch'] ?? null; // nullable
            $key = ($row['goods_erp_id'] ?? '') . '|' . ($batch ?? '') . '|' . ($row['warehouse_erp_id'] ?? '');
            $deduped[$key] = [
                'goods_erp_id'     => $row['goods_erp_id'],
                'batch'            => $batch,
                'warehouse_erp_id' => $row['warehouse_erp_id'],
                'quantity'         => $row['quantity'],
            ];
        }

        $now = Carbon::now();
        $companyId = app(AuthContextService::class)->getCompanyId();

        // Prepare rows for upsert (ensure id, creator_company_id, timestamps for inserts)
        $toUpsert = [];
        foreach ($deduped as $data) {
            $toUpsert[] = [
                'id'                => (string) Str::uuid(),
                'goods_erp_id'      => $data['goods_erp_id'],
                'batch'             => $data['batch'],
                'warehouse_erp_id'  => $data['warehouse_erp_id'],
                'quantity'          => $data['quantity'],
                'creator_company_id'=> $companyId,
                'created_at'        => $now,
                'updated_at'        => $now,
                'deleted_at'        => null,
            ];
        }

        // Detect existing records to approximate created vs updated counters
        $existingCount = 0;
        if (!empty($toUpsert)) {
            $query = LeftoverErp::query();
            $first = true;
            foreach ($toUpsert as $item) {
                $clause = function ($q) use ($item) {
                    $q->where('goods_erp_id', $item['goods_erp_id'])
                      ->where('warehouse_erp_id', $item['warehouse_erp_id']);
                    if (is_null($item['batch'])) {
                        $q->whereNull('batch');
                    } else {
                        $q->where('batch', $item['batch']);
                    }
                };
                if ($first) {
                    $query->where($clause);
                    $first = false;
                } else {
                    $query->orWhere($clause);
                }
            }
            $existingCount = $query->count();
        }

        // Perform upsert
        LeftoverErp::query()->upsert(
            $toUpsert,
            ['goods_erp_id', 'batch', 'warehouse_erp_id'],
            ['quantity', 'updated_at', 'deleted_at']
        );

        $processed = count($toUpsert);
        $updated   = min($existingCount, $processed);
        $created   = max(0, $processed - $updated);

        return [
            'processed' => $processed,
            'updated'   => $updated,
            'created'   => $created,
        ];
    }

    public function upsertOne(array $data): LeftoverErp
    {
        $companyId = app(AuthContextService::class)->getCompanyId();

        $attributes = [
            'goods_erp_id'     => $data['goods_erp_id'],
            'batch'            => $data['batch'] ?? null,
            'warehouse_erp_id' => $data['warehouse_erp_id'],
        ];

        // Try to find including soft-deleted records by unique key
        $existing = LeftoverErp::withTrashed()
            ->where($attributes)
            ->first();

        if ($existing) {
            // Update quantity and restore if soft-deleted
            $existing->quantity = $data['quantity'];
            if ($existing->trashed()) {
                $existing->restore();
            }
            $existing->save();
            return $existing->refresh();
        }

        // Create new
        $values = [
            'quantity'           => $data['quantity'],
            'creator_company_id' => $companyId,
        ];

        /** @var LeftoverErp $model */
        $model = LeftoverErp::query()->create($attributes + $values);
        return $model->refresh();
    }
}
