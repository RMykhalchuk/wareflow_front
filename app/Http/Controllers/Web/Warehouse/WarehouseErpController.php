<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Warehouse\WarehouseErpRequest;
use App\Http\Resources\Web\WarehouseErpResource;
use App\Models\Entities\WarehouseComponents\WarehouseErp;
use App\Services\Web\Warehouse\WarehouseServiceInterface;
use App\Tables\WarehouseErp\TableFacade;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * WarehouseErpController.
 */
final class WarehouseErpController extends Controller
{

    /**
     * @param WarehouseServiceInterface $warehouseService
     */
    public function __construct(private readonly WarehouseServiceInterface $warehouseService)
    {
        $this->middleware('can:view-dictionaries')->only([
            'index',
            'create',
            'store',
            'show',
            'edit',
            'update',
            'destroy',
            'filter',
        ]);
    }

    /**
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $warehouseCount = WarehouseErp::count();

        return view('warehouse-erp.index', compact('warehouseCount'));
    }

    /**
     * @return mixed
     */
    public function filter()
    {
        return TableFacade::getFilteredData();
    }

    /** Get Warehouses Erp */
    #[QueryParameter('page', 'int', required: false, example: 2)]
    public function getWarehouses(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);

        return response()->json(WarehouseErp::orderBy('id', 'desc')->paginate($perPage));
    }

    /** Get warehouse erp by id
     *
     * Get one warehouse erp by UUID
     * */
    public function getWarehouseById($id): JsonResponse
    {
        return response()->json(WarehouseErp::findOrFail($id));
    }


    /** Create Warehouse erp */
    public function store(WarehouseErpRequest $request): WarehouseErpResource
    {
        return WarehouseErpResource::make($this->warehouseService->storeWarehouseErp($request));
    }

    /**
     * Update Warehouse erp by uuid
     * @urlParam warehouse uuid required ID warehouse. Example: 6f9a5c16-3eab-45aa-9f0b-31e9c73d1d6f
     */
    public function update(WarehouseErpRequest $request, WarehouseErp $warehouseErp): WarehouseErpResource
    {
        return WarehouseErpResource::make($this->warehouseService->updateWarehouseErp($request, $warehouseErp));
    }

    /**
     * Delete Warehouse erp by uuid
     * @urlParam  Warehouse $warehouse  The Warehouse being deleted.
     */

    public function destroy(WarehouseErp $warehouseErp): Response|ResponseFactory
    {
        $warehouseErp->delete();
        return response(null, 204);
    }
}
