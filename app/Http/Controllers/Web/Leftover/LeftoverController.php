<?php

namespace App\Http\Controllers\Web\Leftover;

use App\Http\Controllers\Controller;

use App\Http\Requests\Web\StoreLeftoverRequest;
use App\Http\Resources\Web\LeftoverPackageResource;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use App\Services\Web\Document\ReserveLeftover\ReserveLeftoverInterface;
use App\Services\Web\Leftover\LeftoverServiceInterface;
use App\Tables\Leftover\TableFacade as Filter;
use App\Tables\LeftoverByParty\TableFacade as FilterByParty;
use App\Tables\LeftoverByPartyAndPacking\TableFacade as FilterByPartyAndPackage;
use App\View\Components\Layout\Container;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LeftoverController extends Controller
{


    public function __construct(private LeftoverServiceInterface $leftoverService, private ReserveLeftoverInterface $reserveLeftoverService) {}

    public function index(): View
    {
        $leftoverCount = Leftover::count();

        return view('leftovers.index', compact('leftoverCount'));
    }

    public function filter(Request $request)
    {
        $warehouseId = $request->get('warehouses_ids') ?? [\Auth::user()->currentWarehouseId];
        $cellId = $request->get('cell_id') ?? null;
        return Filter::getFilteredData($warehouseId, $cellId);
    }

    public function filterByParty(Request $request)
    {
        return FilterByParty::getFilteredData($request->get('warehouses_ids'));
    }

    public function filterByPartyAndPackage()
    {
        return FilterByPartyAndPackage::getFilteredData();
    }

    public function addByDocument(Request $request, Document $document): JsonResponse
    {
        $result = $this->leftoverService->addByDocument($request, $document);
        return response()->json(['message' => $result['message']])->setStatusCode($result['status']);
    }

    public function removeByDocument(Request $request, Document $document): JsonResponse
    {
        $result = $this->leftoverService->removeByDocument($request, $document);
        return response()->json(['message' => $result['message']])->setStatusCode($result['status']);
    }

    public function moveByDocument(Request $request, Document $document): JsonResponse
    {
        $result = $this->leftoverService->moveByDocument($request, $document);
        return response()->json(['message' => $result['message']])->setStatusCode($result['status']);
    }

    /**
     * Add leftovers
     */
    public function store(StoreLeftoverRequest $request): JsonResponse
    {
        $data = $request->validated();
        $leftover = Leftover::create($data);

        if ($data['container_id']) {
            ContainerRegister::where('id', $data['container_id'])->update(['cell_id' => $data['cell_id']]);
        }

        return response()->json($leftover, 201);
    }

    public function getPackageInfo(Leftover $leftover)
    {
        $packages = $this->leftoverService->calculatePackage($leftover);

        return LeftoverPackageResource::collection(
            $packages->map(fn($package) => new LeftoverPackageResource($package, $leftover))
        );
    }

    public function getAvailableLeftovers(Goods $goods)
    {
        [$availableMainUnitsCount, $quantityInPackage] = $this->reserveLeftoverService->checkAvailableLeftovers($goods->id);
        return response()->json(
            [
                'count' => $availableMainUnitsCount,
                'quantity_in_package' => $quantityInPackage
            ]
        );
    }
}
