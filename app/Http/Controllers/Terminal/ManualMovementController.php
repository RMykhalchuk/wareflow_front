<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Terminal\MoveLeftoversOrContainerRequest;
use App\Models\Entities\Container\ContainerRegister;
use App\Services\Terminal\InternalMovement\Manual\ManualMovementServiceInterface;
use Illuminate\Http\Request;

/**
 * Manual Movement API
 */
class ManualMovementController extends Controller
{

    public function __construct(private ManualMovementServiceInterface $manualMovementService) {}

    /**
     * Get Container Register Data by uuid
     */
    public function scanContainer(string $id)
    {
        $containerRegister = ContainerRegister::inCurrentWarehouse()->findOrFail($id);
        return response()->json($this->manualMovementService->scanContainer($containerRegister));
    }

    /**
     * Get Cell Data by uuid
     */
    public function scanCell(string $id)
    {
        return response()->json($this->manualMovementService->scanCell($id));
    }

    /**
     * Move Leftover Or Container to Cell
     */

    public function move(MoveLeftoversOrContainerRequest $request)
    {
        return response()->json($this->manualMovementService->move($request));
    }

    /**
     * Search cells and containers by code
     */

    public function getCellAndContainer(Request $request)
    {
        $validated = $request->validate(
            [
                'query' => 'required|string|min:3',
            ]);

        return response()->json($this->manualMovementService->getContainerAndCell($validated['query']));
    }

}
