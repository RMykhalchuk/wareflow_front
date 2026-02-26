<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Warehouse\Row\RowRequest;
use App\Http\Resources\Web\RowResource;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Services\Web\Warehouse\Row\RowServiceInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @group Rows
 *
 * Methods to control warehouse rows
 */

class RowController extends Controller
{

    /**
     * @param RowServiceInterface $rowService
     */
    public function __construct(private RowServiceInterface $rowService)
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
     * @param Sector $sector
     * @return Factory|View
     */
    public function index(Sector $sector): Factory|View
    {
        $sector->load(['zone','rows']);

        return view('warehouse.row.index', compact('sector'));
    }


    /** Create Row */
    public function store(Sector $sector, RowRequest $request): RowResource
    {
        return new RowResource($this->rowService->create($sector,$request->validated()));
    }

    /**
     * Update Row by id
     * @param  Row  $row  The sector being updated.
     */

    public function update(RowRequest $request,Sector $sector, Row $row): RowResource
    {
        return new RowResource($this->rowService->update($sector,$row,$request->validated()));
    }

    /**
     * Delete Row by id
     * @param Row $row  The row being deleted.
     */
    public function destroy(Sector $sector,Row $row): Response|ResponseFactory
    {
        $this->rowService->delete($sector,$row);

        return response(null,204);
    }

    /**
     * Check if u can delete sector by uuid
     * @urlParam  WarehouseZone  $zone  The zone being checked.
     */
    public function canDelete(Row $row): JsonResponse
    {
        return response()->json(
            [
                'can_delete' => !$row->hasLeftovers()
            ]);
    }
}
