<?php

namespace App\Http\Controllers\Web\Registers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Registers\RegisterRequest;
use App\Http\Resources\Web\RegisterResurce;
use App\Http\Resources\Web\UserResource;
use App\Models\Dictionaries\Register;
use App\Models\User;
use App\Services\Web\Registers\PaginationPage;
use App\Services\Web\Registers\RegisterServiceInterface;
use App\Tables\Registers\TableFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

final class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(RegisterServiceInterface $registerService)
    {
        $this->registerService = $registerService;
    }

    public function guardian(): View
    {
        $data = $this->registerService->prepareGuardianData();
        return view('registers.guardian', [
            'storekeepers' => UserResource::collection($data['storekeepers']),
            'warehouses' => $data['warehouses'],
        ]);
    }

    public function storekeeper(): View
    {
        $data = $this->registerService->prepareStorekeeperData();
        return view('registers.storekeeper', [
            'storekeepers' => UserResource::collection($data['storekeepers']),
            'managers' => UserResource::collection($data['managers']),
            'downloadZone' => RegisterResurce::collection($data['downloadZone']),
            'transportDownload' => RegisterResurce::collection($data['transportDownload']),
            'warehouses' => $data['warehouses'],
        ]);
    }

    public function filter(Request $request)
    {
        return response()->json(TableFacade::getFilteredData($request->get('warehouses_ids')));
    }

    public function create(): View
    {
        return view('registers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->registerService->storeRegister($request);
        return redirect()->back();
    }

    public function delete(Register $register): Response
    {
        $register->delete();
        return response('ok');
    }

    public function update(RegisterRequest $request, Register $register): Response
    {
        $register->updateWithRelations($request->validated());
        return response('ok');
    }

    public function getStorekeepers(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all(['id', 'surname', 'name']));
    }

    public function getManagers(): AnonymousResourceCollection
    {
        return UserResource::collection(User::all(['id', 'surname', 'name']));
    }

    public function getPageByRegister(Request $request, PaginationPage $pager): JsonResponse
    {
        return response()->json(
            [
                'page' => $pager->getPageByRegisterId($request->id, $request->pager_rows, $request->sort)
            ]);
    }
}
