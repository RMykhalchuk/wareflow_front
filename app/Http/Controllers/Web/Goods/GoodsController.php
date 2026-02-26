<?php

namespace App\Http\Controllers\Web\Goods;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Goods\GoodsKitsItemsRequest;
use App\Http\Requests\Web\Goods\GoodsRequest;
use App\Models\Entities\Goods\Goods;
use App\Services\Web\Goods\GoodsServiceInterface;
use App\Tables\Goods\TableFacade;
use App\Tables\GoodsBarcode\TableFacade as BarcodeTable;
use App\Tables\GoodsPackage\TableFacade as PackageTable;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

/**
 * GoodsController.
 */
final class GoodsController extends Controller
{
    /**
     * @var GoodsServiceInterface
     */
    protected $goodsService;

    /**
     * @param GoodsServiceInterface $goodsService
     */
    public function __construct(GoodsServiceInterface $goodsService)
    {
        $this->goodsService = $goodsService;

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
     * @return mixed
     */
    public function filter(): mixed
    {
        return TableFacade::getFilteredData();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function skuList(Request $request): JsonResponse
    {
        return response()->json($this->goodsService->list($request->query()));
    }

    /**
     * @param Goods $sku
     * @return mixed
     */
    public function packageFilter(Goods $sku): mixed
    {
        return PackageTable::getFilteredData($sku->id);
    }

    /**
     * @param Goods $sku
     * @return mixed
     */
    public function barcodeFilter(Goods $sku): mixed
    {
        return BarcodeTable::getFilteredData($sku->id);
    }

    /**
     * @param GoodsRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(GoodsRequest $request): JsonResponse
    {
        $goodsId = $this->goodsService->store($request);

        return response()->json(['goods_id' => $goodsId]);
    }

    /**
     * @param GoodsKitsItemsRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function storeKits(GoodsKitsItemsRequest $request): JsonResponse
    {
        $goodsId = $this->goodsService->storeKits($request);

        return response()->json(['goods_id' => $goodsId]);
    }

    /**
     * @param GoodsRequest $request
     * @param Goods $goods
     * @return JsonResponse
     */
    public function update(GoodsRequest $request, Goods $goods): JsonResponse
    {
        $this->goodsService->update($goods, $request);

        return response()->json(['status' => 'success']);
    }

    /**
     * @param GoodsKitsItemsRequest $request
     * @param Goods $goods
     * @return JsonResponse
     */
    public function updateKits(GoodsKitsItemsRequest $request, Goods $goods): JsonResponse
    {
        $this->goodsService->updateKits($goods, $request);

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Goods $sku
     * @return View
     */
    public function show(Goods $sku): View
    {
        if ($sku->is_kit) {
            return view('sku.kits.view', $this->goodsService->prepareShowData($sku));
        } else {
            return view('sku.full-info', $this->goodsService->prepareShowData($sku));
        }
    }

    /**
     * @param Goods $sku
     * @return View
     */
    public function edit(Goods $sku): View
    {
        return view('sku.edit', $this->goodsService->prepareEditData($sku));
    }

    /**
     * @param Goods $sku
     * @return View
     */
    public function editKit(Goods $sku): View
    {
        return view('sku.kits.edit', $this->goodsService->prepareEditData($sku));
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('sku.create', $this->goodsService->prepareCreateData());
    }

    /**
     * @return View
     */
    public function createKits(): View
    {
        return view('sku.kits.create', $this->goodsService->prepareCreateData());
    }

    /**
     * @return View
     */
    public function index(): View
    {
        $goodsCount = Goods::count();

        return view('sku.index', ['goodsCount' => $goodsCount]);
    }

    /**
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function getSkuByCategory($id): AnonymousResourceCollection
    {
        return JsonResource::collection($this->goodsService->getSkuByCategory($id));
    }

    /**
     * @param Goods $sku
     * @return JsonResource
     */
    public function getAllData(Goods $sku): JsonResource
    {
        return JsonResource::make($sku);
    }

    /**
     * @param Goods $sku
     * @return Response|RedirectResponse|ResponseFactory
     */
    public function destroy(Goods $sku): Response|RedirectResponse|ResponseFactory
    {
        if($sku->hasLeftovers()){
            return response("This product already used in leftovers", 403);
        }
        $sku->delete();

        return redirect()->route('sku.index');
    }
}
