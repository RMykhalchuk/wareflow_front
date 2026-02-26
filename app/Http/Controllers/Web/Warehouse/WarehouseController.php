<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Warehouse\WarehouseRequest;
use App\Http\Requests\Web\Warehouse\WarehouseScheduleRequest;
use App\Http\Resources\Web\WarehouseResource;
use App\Models\Dictionaries\WarehouseType;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Services\Web\Warehouse\WarehouseServiceInterface;
use App\Tables\Warehouse\TableFacade;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * WarehouseController.
 */
final class WarehouseController extends Controller
{

    /**
     * @param WarehouseServiceInterface $warehouseService
     */
    public function __construct(private WarehouseServiceInterface $warehouseService)
    {
        $this->middleware('can:view-dictionaries')
            ->only([
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
        $warehouseCount = Warehouse::count();
        return view('warehouse.index', compact('warehouseCount'));
    }

    /** Get Warehouses */
    #[QueryParameter('page', 'int', required: false, example: 2)]
    public function getWarehouses(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 15);

        return response()->json(Warehouse::orderBy('id', 'desc')->paginate($perPage));
    }

    /** Get warehouse by id
     *
     * Get one warehouse by UUID
     * */
    public function getWarehouseById($id): JsonResponse
    {
        return response()->json(Warehouse::findOrFail($id));
    }

    /** Get warehouse types
     *
     * Get the warehouse types needed to create a warehouse {warehouse.type_id}
     * */
    public function getWarehouseTypes(): JsonResponse
    {
        return response()->json(WarehouseType::all());
    }

    /**
     * @return Factory|View
     */
    public function create(): Factory|View
    {
        return view('warehouse.create', $this->warehouseService->getCreateFormData());
    }

    /** Create Warehouse */
    public function store(WarehouseRequest $request): WarehouseResource
    {
        return WarehouseResource::make($this->warehouseService->storeWarehouse($request));
    }

    /**
     * @param Warehouse $warehouse
     * @return Factory|View
     */
    public function show(Warehouse $warehouse): Factory|View
    {
        return view('warehouse.revision', $this->warehouseService->getRevisionData($warehouse));
    }

    /**
     * @param Warehouse $warehouse
     * @return Factory|View
     */
    public function edit(Warehouse $warehouse): Factory|View
    {
        return view('warehouse.data-edit', $this->warehouseService->getEditFormData($warehouse));
    }

    /**
     * Update Warehouse by uuid
     * @urlParam warehouse uuid required ID warehouse. Example: 6f9a5c16-3eab-45aa-9f0b-31e9c73d1d6f
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse): WarehouseResource
    {
        return WarehouseResource::make($this->warehouseService->updateWarehouse($request, $warehouse));
    }


    /**
     * @param WarehouseScheduleRequest $request
     * @param Warehouse $warehouse
     * @return Response|ResponseFactory
     */
    public function updateSchedule(WarehouseScheduleRequest $request, Warehouse $warehouse): Response|ResponseFactory
    {
        $this->warehouseService->updateSchedule($request, $warehouse);

        return response('OK');
    }

    /**
     * Delete Warehouse by uuid
     * @urlParam  Warehouse $warehouse  The Warehouse being deleted.
     */

    public function destroy(Warehouse $warehouse): Response|ResponseFactory
    {
        $this->warehouseService->destroyWarehouse($warehouse);

        return response(null, 204);
    }


    /**
     * @return mixed
     */
    public function filter()
    {
        return TableFacade::getFilteredData();
    }

    /**
     * @param Request $request
     * @param WarehouseServiceInterface $service
     * @return JsonResponse
     */
    public function options(Request $request, WarehouseServiceInterface $service): JsonResponse
    {
        return response()->json([
                                    'data' => $service->listOptions(
                                        $request->query('creator_company_id'),
                                        $request->query('company_id')
                                    ),
                                ]);
    }
}
