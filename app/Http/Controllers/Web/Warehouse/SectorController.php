<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Warehouse\Sector\SectorRequest;
use App\Http\Resources\Web\SectorResource;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Services\Web\Warehouse\Sector\SectorService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;


/**
 * @group Sectors
 *
 * Methods to control warehouse sectors
 */
class SectorController extends Controller
{

    /**
     * @param SectorService $sectorService
     */
    public function __construct(private SectorService $sectorService)
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
     * @param WarehouseZone $zone
     * @return Factory|View
     */
    public function index(WarehouseZone $zone): Factory|View
    {
        $zone->load('sectors.cells');
        $zone->sectors = $zone->sectors->sortBy(fn($s) => strtolower($s->code));

        return view('warehouse.sector.index', compact('zone'));
    }

    /** Create Sector */

    public function store(WarehouseZone $zone, SectorRequest $request): SectorResource
    {
        $sector = $this->sectorService->create($zone, $request->validated());

        return new SectorResource($sector);
    }

    /**
     * Update Sector by uuid
     * @param Sector $sector The sector being updated.
     */

    public function update(WarehouseZone $zone, SectorRequest $request, Sector $sector): SectorResource
    {
        $sector = $this->sectorService->update($zone, $sector, $request->validated());

        return new SectorResource($sector);
    }

    /**
     * Delete Sector by uuid
     * @param Sector $sector The sector being deleted.
     */
    public function destroy(WarehouseZone $zone, Sector $sector): Response|ResponseFactory
    {
        $this->sectorService->delete($zone, $sector);

        return response(null, 204);
    }

    /**
     * Check if u can delete sector by uuid
     * @urlParam  WarehouseZone  $zone  The zone being checked.
     */
    public function canDelete(Sector $sector): JsonResponse
    {
        return response()->json(
            [
                'can_delete' => !$sector->hasLeftovers()
            ]);
    }
}
