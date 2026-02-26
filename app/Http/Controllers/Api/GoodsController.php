<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Goods\GoodsRequest;
use App\Http\Requests\Api\Goods\UpdateGoodsRequest;
use App\Http\Resources\Api\GoodsResource;
use App\Models\Entities\Goods\Goods;
use App\Services\Api\Goods\GoodsService;
use App\Services\Api\Goods\GoodsServiceInterface;
use Dedoc\Scramble\Attributes\QueryParameter;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GoodsController extends Controller
{
    protected GoodsService $service;

    public function __construct(private readonly GoodsServiceInterface $goodsService){}

    /**
     * Get goods list
     *
     * @queryParam per_page int optional Items per page. Example: 15
     * @queryParam search string optional Search goods by name. Example: Apple
     */
    #[QueryParameter('per_page', 'int', required: false, example: 15)]
    #[QueryParameter('search', 'string', required: false, example: 'Apple')]
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->goodsService->paginate($request));
    }


    /**
     * Goods view
     */

    public function show(Goods $goods)
    {
        $goods->load(['providerCompany', 'brandCompany', 'manufacturerCompany', 'packages','category']);
        return GoodsResource::make($goods);
    }

    /**
     * Create new goods
     */

    public function store(GoodsRequest $request): JsonResponse
    {
        $goodsId = $this->goodsService->store($request);

        return response()->json(['goods_id' => $goodsId], 201);
    }

    /**
     * Update goods
     */
    // Reuse same body parameters as store — Scramble will merge/interpret them.
    public function update(GoodsRequest $request, Goods $goods): JsonResponse
    {
        $this->goodsService->update($goods, $request);

        return response()->json(['status' => 'success']);
    }

    /**
     * Delete goods
     */

    public function destroy(Goods $goods): Response
    {
        if($goods->hasLeftovers()){
            return response("This product already used in leftovers", 403);
        }
        $goods->delete();
        return response(null, 204);
    }
}
