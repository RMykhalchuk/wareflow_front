<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\LeftoverErp\LeftoverErpCrudRequest;
use App\Http\Requests\Web\LeftoverErp\LeftoverErpBulkUpsertRequest;
use App\Models\Entities\LeftoverErp\LeftoverErp;
use App\Services\Web\LeftoverErp\LeftoverErpServiceInterface;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LeftoverErpController extends Controller
{
    public function __construct(private readonly LeftoverErpServiceInterface $service) {}

    /**
     * List leftovers ERP with pagination
     */
    #[QueryParameter('page', 'int', required: false, example: 1)]
    #[QueryParameter('per_page', 'int', required: false, example: 1)]
    public function getLeftovers(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        return response()->json($this->service->paginate($perPage));
    }

    /**
     * Get single leftover ERP by UUID
     */
    public function getLeftoverById(string $id): JsonResponse
    {
        return response()->json($this->service->find($id));
    }

    /**
     * Create leftover ERP
     *
     * Create/update based on warehouse_erp_id,goods_erp_id,batch
     */
    public function store(LeftoverErpCrudRequest $request): JsonResponse
    {
        // Upsert by unique key: goods_erp_id + batch + warehouse_erp_id
        $upserted = $this->service->upsertOne($request->validated());
        return response()->json($upserted);
    }

    /**
     * Bulk create/update (upsert) leftovers ERP
     *
     * Accepts array of items:
     * [
     *   {"warehouse_erp_id":"...","goods_erp_id":"...","batch":"string|null","quantity":15},
     *   ...
     * ]
     */
    public function bulkUpsert(LeftoverErpBulkUpsertRequest $request): JsonResponse
    {
        $result = $this->service->bulkUpsert($request->validated());
        return response()->json($result);
    }

    /**
     * Update leftover ERP by UUID
     */
    public function update(LeftoverErpCrudRequest $request, LeftoverErp $leftover_erp): JsonResponse
    {
        $updated = $this->service->update($leftover_erp, $request->validated());
        return response()->json($updated);
    }

    /**
     * Delete leftover ERP by UUID
     */
    public function destroy(LeftoverErp $leftover_erp): JsonResponse
    {
        $this->service->delete($leftover_erp);
        return response()->json(null, 204);
    }
}
