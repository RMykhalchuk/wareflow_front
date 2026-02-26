<?php

namespace App\Http\Controllers\Web\Container;

use App\Enums\ContainerRegister\ContainerRegisterStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ContainerRegister\ContainerRegisterRequest;
use App\Http\Requests\Web\ContainerRegisterCellRequest;
use App\Models\Entities\Container\ContainerRegister;
use App\Services\Web\ContainerRegister\ContainerRegisterServiceInterface;
use App\Tables\ContainerRegister\TableFacade;
use Illuminate\Http\JsonResponse;


class ContainerRegisterController extends Controller
{
    public function __construct(private ContainerRegisterServiceInterface $registerService) {}

    public function store(ContainerRegisterRequest $request): JsonResponse
    {
        $containerRegister = $this->registerService->store($request->validated());

        return response()->json($containerRegister, 201);
    }

    public function show(ContainerRegister $containerRegister)
    {
        return view('container-register.view', ['containerRegister' => $containerRegister]);
    }

    public function index()
    {
        $containerRegisterCount = ContainerRegister::count();
        return view('container-register.index', compact('containerRegisterCount'));
    }

    public function filter(TableFacade $filter)
    {
        return $filter->getFilteredData();
    }

    public function assignCell(ContainerRegisterCellRequest $request, ContainerRegister $containerRegister)
    {
        $containerRegister->update($request->validated());

        return response()->json(
            [
                'message' => 'Cell assigned successfully',
                'data' => $containerRegister
            ]);
    }

    public function destroy(ContainerRegister $containerRegister)
    {
        $containerRegister->update(
            [
                'status_id' => ContainerRegisterStatus::DEACTIVATED
            ]);

        return response('OK');
    }
}
