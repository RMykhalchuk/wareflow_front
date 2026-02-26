<?php

namespace App\Http\Controllers\Web\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Warehouse\Cell\CellRequest;
use App\Http\Resources\Web\CellResource;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use App\Services\Web\Warehouse\Cell\CellServiceInterface;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


/**
 * @group Cells
 *
 * Methods to control warehouse cells
 */
class CellController extends Controller
{
    /**
     * @param CellServiceInterface $cellService
     */
    public function __construct(private CellServiceInterface $cellService)
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
     * @param Row $row
     * @return Factory|View
     */
    public function index(Row $row): Factory|View
    {
        $row->load('cells.rowInfo','sector.zone');

        return view('warehouse.cell.index', compact('row'));
    }


    /**
     * Update Cell by id
     * @param Cell $cell The cell being updated.
     */

    public function update(Row $row, CellRequest $request, Cell $cell): CellResource
    {
        return new CellResource($this->cellService->update($row, $cell, $request->validated()));
    }

    /**
     * Delete Cell by id
     * @param Cell $cell The cell being deleted.
     */
    public function destroy(Row $row, Cell $cell): Response|ResponseFactory
    {
        $this->cellService->delete($row, $cell);

        return response(null, 204);
    }

    /**
     * Check if u can delete cell by uuid
     * @urlParam  WarehouseZone  $zone  The zone being checked.
     */
    public function canDelete(Cell $cell): JsonResponse
    {
        return response()->json(
            [
                'can_delete' => !$cell->hasLeftovers()
            ]);
    }
}
