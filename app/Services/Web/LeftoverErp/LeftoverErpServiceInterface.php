<?php

namespace App\Services\Web\LeftoverErp;

use App\Models\Entities\LeftoverErp\LeftoverErp;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeftoverErpServiceInterface
{
    /**
     * Paginate leftovers ERP list
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find one by id
     */
    public function find(string $id): LeftoverErp;

    /**
     * Create new record
     */
    public function store(array $data): LeftoverErp;

    /**
     * Update record
     */
    public function update(LeftoverErp $leftoverErp, array $data): LeftoverErp;

    /**
     * Delete record
     */
    public function delete(LeftoverErp $leftoverErp): void;

    /**
     * Bulk create/update (upsert) leftovers by unique key
     *
     * Unique key: goods_erp_id + batch + warehouse_erp_id
     *
     * @param array<int, array<string, mixed>> $rows
     * @return array{processed:int, updated:int, created:int}
     */
    public function bulkUpsert(array $rows): array;

    /**
     * Upsert a single leftover ERP by unique key (goods_erp_id + batch + warehouse_erp_id).
     */
    public function upsertOne(array $data): LeftoverErp;
}
