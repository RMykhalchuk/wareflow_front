<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Warehouse\Zone\ZoneRequest;
use App\Http\Resources\Web\WarehouseZoneResource;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Services\Web\Warehouse\Zone\ZoneServiceInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


/**
 * @group Warehouse Zones
 *
 * Methods to control warehouse zones
 */
class ZoneController extends Controller
{

    /**
     * @param ZoneServiceInterface $zoneService
     */
    public function __construct(private ZoneServiceInterface $zoneService)
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
     * @param Warehouse $warehouse
     * @return Factory|View
     */
    public function index(Warehouse $warehouse): Factory|View
    {
        $warehouse->load([
            'zones.zoneType',
            'zones.zoneSubtype',
            'zones.cells',
        ]);

        return view('warehouse.zone.index', compact('warehouse'));
    }

    /** Create Zone */
    public function store(Warehouse $warehouse, ZoneRequest $request): WarehouseZoneResource
    {
        $zone = $this->zoneService->create($warehouse, $request->validated());

        return new WarehouseZoneResource($zone);
    }

    /**
     * Update Zone by uuid
     * @urlParam warehouse uuid required ID warehouse. Example: 6f9a5c16-3eab-45aa-9f0b-31e9c73d1d6f
     * @urlParam  WarehouseZone  $zone  The zone being updated. Example: 6f9a5c16-3eab-45aa-9f0b-31e9c73d1d6f
     */

    public function update(Warehouse $warehouse, ZoneRequest $request, WarehouseZone $zone): WarehouseZoneResource
    {
        $zone = $this->zoneService->update($warehouse, $zone, $request->validated());

        return new WarehouseZoneResource($zone);
    }

    /**
     * Delete Zone by uuid
     * @urlParam  WarehouseZone  $zone  The zone being deleted.
     */
    public function destroy(Warehouse $warehouse, WarehouseZone $zone): Response|ResponseFactory
    {
        $this->zoneService->delete($warehouse, $zone);

        return response(null, 204);
    }

    /**
     * Check if u can delete zone by uuid
     * @urlParam  WarehouseZone  $zone  The zone being checked.
     */
    public function canDelete(WarehouseZone $zone): JsonResponse
    {
        return response()->json(
            [
                'can_delete' => !$zone->hasLeftovers()
            ]);
    }
}
