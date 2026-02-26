<?php

namespace App\Http\Controllers\Web\Leftover;

use App\Http\Controllers\Controller;

use App\Http\Requests\Web\ContainerRegister\LeftoverToContainerRegisterRequest;
use App\Models\Entities\Leftover\LeftoverToContainerRegister;
use App\Services\Web\LeftoverToContainer\LeftoverToContainerRegisterServiceInterface;
use App\Tables\GoodsToContainerRegister\TableFacade;
use Illuminate\Http\JsonResponse;

class LeftoverToContainerController extends Controller
{
    public function __construct(private LeftoverToContainerRegisterServiceInterface $leftoverToContainerService) {}

    public function store(LeftoverToContainerRegisterRequest $request): JsonResponse
    {
        $created = $this->leftoverToContainerService->store($request);

        return response()->json($created, 201);
    }


    public function filter(TableFacade $filter, string $containerRegisterId)
    {
        return $filter->getFilteredData($containerRegisterId);
    }

    public function destroy(LeftoverToContainerRegister $leftoverToContainerRegister): JsonResponse
    {
        $leftoverToContainerRegister->delete();

        return response()->json(null, 204);
    }
}
