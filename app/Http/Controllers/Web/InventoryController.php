<?php

namespace App\Http\Controllers\Web;

use App\Enums\Inventory\InventoryItemStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Inventory\InventoryRequest;
use App\Http\Requests\Web\Inventory\LeftoverRequest;
use App\Http\Requests\Web\Inventory\LeftoversCorrectQtyRequest;
use App\Http\Requests\Web\Inventory\LeftoverUpdateRequest;
use App\Http\Requests\Web\Inventory\LeftoverWebRequest;
use App\Models\Entities\Inventory\Inventory;
use App\Models\Entities\Inventory\InventoryItem;
use App\Models\Entities\Inventory\InventoryLeftover;
use App\Services\Terminal\Inventory\InventoryItemServiceInterface;
use App\Services\Terminal\Inventory\InventoryLeftoverServiceInterface;
use App\Services\Terminal\Inventory\InventoryManualServiceInterface;
use App\Services\Terminal\Inventory\InventoryServiceInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Inventory web controller.
 */
class InventoryController extends Controller
{
    /**
     * @param InventoryServiceInterface $service
     * @param InventoryItemServiceInterface $itemService
     * @param InventoryManualServiceInterface $inventoryManualService
     */
    public function __construct(
        private readonly InventoryServiceInterface $service,
        private readonly InventoryItemServiceInterface $itemService,
        private readonly InventoryManualServiceInterface $inventoryManualService
    ) {}

    /**
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $inventoryCount = Inventory::withTrashed()->count();

        return view('inventory.index', compact('inventoryCount'));
    }

    /**
     * @return Factory|View
     */
    public function manual(): Factory|View
    {
        $inventoryCount = $this->inventoryManualService->getList()['total'] ?? 0;

        return view('inventory.manual.index', compact('inventoryCount'));
    }

    /**
     * @return Factory|View
     */
    public function create(): Factory|View
    {
        $lastLocalId = Inventory::max('local_id') + 1;

        return view('inventory.create', compact('lastLocalId'));
    }

    /**
     * @param InventoryRequest $request
     * @return JsonResponse
     */
    public function store(InventoryRequest $request): JsonResponse
    {
        [$inventory, $generatedCount] = $this->service->store($request->validated());

        return response()->json([
            'inventory' => $inventory->toArray(),
            'items_generated' => (int) $generatedCount,
            ],
            201);
    }

    /**
     * @param Inventory $inventory
     * @return Factory|View
     */
    public function show(string $id): Factory|View
    {
        $inventory = Inventory::withTrashed()->findOrFail($id);
        $inventory->load('goods');
        return view('inventory.view', compact('inventory'));
    }

    /**
     * @param Inventory $inventory
     * @return Factory|View
     */
    public function edit(Inventory $inventory): Factory|View
    {
        $inventory->load('goods');
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * @param InventoryRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(InventoryRequest $request, string $id): RedirectResponse
    {
        $inventory = $this->service->get($id);
        $this->service->update($inventory, $request->validated());

        return redirect()
            ->route('inventory.show', $inventory->id)
            ->with('success', 'Inventory updated');
    }

    /**
     * @param Inventory $inventory
     * @return RedirectResponse
     */
    public function destroy(Inventory $inventory): RedirectResponse
    {
        $this->service->delete($inventory);

        return redirect()->route('inventory.index')->with('success', 'Inventory deleted');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function table(Request $request): JsonResponse
    {
        return response()->json($this->service->gridTableDataFromRequest($request));
    }

    /**
     * @param Request $request
     * @param string $inventory
     * @return JsonResponse
     */
    public function items(Request $request, string $inventory): JsonResponse
    {
        return response()->json($this->itemService->gridItemsDataFromRequest($inventory, $request));
    }

    /**
     * @param Request $request
     * @param string $inventory
     * @return JsonResponse
     */
    public function leftoverItems(Request $request, string $inventory): JsonResponse
    {
        return response()->json($this->itemService->gridLefoversDataFromRequest($inventory, $request));
    }

    /**
     * @param Request $request
     * @param string $inventoryItem
     * @return JsonResponse
     */
    public function leftovers(Request $request, string $inventoryItem) : JsonResponse
    {
        return response()->json($this->itemService->gridLefoversDataByItemFromRequest($inventoryItem, $request));
    }

    /**
     * @param string $id
     * @param string $action
     * @return RedirectResponse
     */
    public function transition(string $id, string $action): RedirectResponse
    {
        $res = $this->service->transition($id, $action);

        return redirect()->route('inventory.show', $res['inventory']->id)
            ->with($res['flash']['key'], $res['flash']['msg']);
    }

    /**
     * @param LeftoverRequest $request
     * @param string $inventory
     * @param InventoryLeftoverServiceInterface $service
     * @return JsonResponse
     */
    public function leftoversStore(
        LeftoverWebRequest $request,
        string $inventory,
        InventoryLeftoverServiceInterface $service
    ): JsonResponse
    {

        $il = $service->createForItem($inventory, $request->validated());

        return response()
            ->json([
                'status'  => 'ok',
                'data'    => ['id' => (string) $il->id],
                'message' => 'Inventory leftover created',
            ], 201);
    }

    /**
     * @param LeftoverUpdateRequest $request
     * @param InventoryLeftover $leftovers
     * @param InventoryLeftoverServiceInterface $service
     * @return JsonResponse
     */
        public function leftoversUpdate(
            LeftoverUpdateRequest $request,
            InventoryLeftover $leftovers,
            InventoryLeftoverServiceInterface $service
        ): JsonResponse
        {
            $il = $service->update($leftovers->id, $request->validated());

            return response()
                ->json([
                    'status'  => 'ok',
                    'data'    => ['id' => (string) $il->id],
                    'message' => 'Inventory leftover updated',
                ]);
        }

    /**
     * @param LeftoversCorrectQtyRequest $request
     * @param string $item
     * @param InventoryLeftoverServiceInterface $leftoverService
     * @return JsonResponse
     */
    public function correctItemQuantity(
        LeftoversCorrectQtyRequest $request,
        string $item,
        InventoryLeftoverServiceInterface $leftoverService
    ): JsonResponse {
        $result = $leftoverService->correctCurrent(
            $item,
            $request->quantity(),
            $request->packageId()
        );

        return response()->json($result);
    }

    /**
     * @param InventoryLeftover $leftover
     * @return JsonResponse
     */
    public function leftoverShow(
        InventoryLeftover $leftover,
    ): JsonResponse {
        return response()->json(['status' => 'ok', 'data' => $leftover]);
    }

    /**
     * @param string $leftover
     * @param InventoryLeftoverServiceInterface $service
     * @return JsonResponse
     */
    public function leftoverDestroy(InventoryLeftover $leftovers): JsonResponse
    {
        $leftovers->delete();

        return response()->json(['status' => 'ok', 'message' => 'Inventory leftover deleted']);
    }

    /**
     * @param InventoryItem $item
     * @param Request $request
     * @return JsonResponse
     */
    public function itemStatus(InventoryItem $item, Request $request): JsonResponse
    {
        return response()->json($this->itemService->update(
            $item,
            ['status' => InventoryItemStatus::INVENTORIED, 'update_id' => auth()->id()]
        ));
    }

    /**
     * @return JsonResponse
     */
    public function getManualItems(): JsonResponse
    {
        return response()->json($this->inventoryManualService->getList());
    }

    /**
     * @return JsonResponse
     */
    public function getManualLeftoversByCell(): JsonResponse
    {
        return response()->json(
            $this->inventoryManualService->getLeftoversByCell(request()->route('cell'))
        );
    }

    /**
     * @return JsonResponse
     */
    public function getManualLeftoversByGroup(): JsonResponse
    {
        return response()->json(
            $this->inventoryManualService->getLeftoversByGroup(request()->route('group'))
        );
    }

    /**
     * @param InventoryLeftover $leftovers
     * @return JsonResponse
     */
    public function getPackages(InventoryLeftover $leftovers): JsonResponse
    {
        $packages = $leftovers->calculatePackage($leftovers);

        return response()->json([
            'leftover_id' => $leftovers->id,
            'packages'    => $packages,
        ]);
    }
}
