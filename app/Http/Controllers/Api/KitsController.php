<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Goods\KitsRequest;
use App\Http\Requests\Api\Goods\UpdateKitsRequest;
use App\Http\Resources\Api\KitResource;
use App\Models\Entities\Goods\Goods;
use App\Services\Web\Goods\GoodsServiceInterface;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KitsController extends Controller
{
    public function __construct(private readonly GoodsServiceInterface $goodsService) {}

    /**
     * Create new kit
     */
    public function store(KitsRequest $request): JsonResponse
    {
        $goodsId = $this->goodsService->storeKits($request);
        return response()->json(['goods_id' => $goodsId]);
    }

    /**
     * Get kits list
     *
     * @queryParam per_page int optional Items per page. Example: 15
     * @queryParam search string optional Search goods by name. Example: Apple
     */
    #[QueryParameter('per_page', 'int', required: false, example: 15)]
    #[QueryParameter('search', 'string', required: false, example: 'Apple')]
    public function index(Request $request): JsonResponse
    {
        $perPage = (int)$request->query('per_page', 15);
        $search = $request->query('search');

        $goodsQuery = Goods::where('is_kit', true);

        if ($search) {
            $goodsQuery->where('name', 'ILIKE', "%{$search}%");
        }

        $goods = $goodsQuery->simplePaginate($perPage);

        return response()->json($goods);
    }

    /**
     * Update kit
     */

    public function update(UpdateKitsRequest $request, Goods $goods): JsonResponse
    {
        $this->goodsService->updateKits($goods, $request);

        return response()->json(['status' => 'success']);
    }

    /**
     * Delete kit
     */

    public function destroy(Goods $sku)
    {
        if($sku->hasLeftovers()){
            return response("This product already used in leftovers", 403);
        }
        $sku->delete();
        return response(null, 204);
    }

    /**
     * Show kit
     */
    public function show(Goods $goods): KitResource
    {
        $goods->load(['packages','category', 'goodsKitItems']);

        return KitResource::make($goods);
    }

}
