<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Web\DictionaryResource;
use App\Services\Web\Company\CompanyDictionaryService;
use App\Services\Web\Dictionary\DictionaryService;
use App\Services\Web\Dictionary\EnumService;
use App\Services\Web\Goods\GoodsService;
use App\Services\Web\Warehouse\Zone\ZoneTypeSubtypeServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Web\Category\CategoryDictionaryService;
use Illuminate\Http\Request;
use App\Models\Entities\WarehouseComponents\Zone\ZoneType;

final class DictionaryController extends Controller
{
    public function getCompanyList(): JsonResource
    {
        return JsonResource::make(new CompanyDictionaryService()->getDictionaryList());
    }

    public function getDictionaryList($dictionary)
    {
        return JsonResource::make(new DictionaryService()->getDictionaryList($dictionary));
    }

    public function getEnumsList($dictionary): JsonResource
    {
        return JsonResource::make(new EnumService()->getDictionaryList($dictionary));
    }

    public function availableDoctypeDictionary(): JsonResponse
    {
        return response()->json(config('directories.doctype_dictionaries'));
    }

    /**
     * @param GoodsService $goodsService
     * @param Request $request
     * @return JsonResponse
     */
    public function getGoodsExpirationDictionary(GoodsService $goodsService, Request $request): JsonResponse
    {
        return response()->json(['data' => $goodsService->getExpirationOptions($request->input('goods_id'))]);
    }

    /**
     * @param ZoneTypeSubtypeServiceInterface $service
     * @return JsonResponse
     */
    public function getZoneTypes(ZoneTypeSubtypeServiceInterface $service): JsonResponse
    {
        return response()->json([
            'data' => $service->getTypes(),
        ]);
    }

    /**
     * @param Request $request
     * @param ZoneTypeSubtypeServiceInterface $service
     * @return JsonResponse
     */
    public function getZoneSubtypes(
        Request $request,
        ZoneTypeSubtypeServiceInterface $service
    ): JsonResponse {
        $typeId = (int) $request->input('zone_type_id');

        return response()->json([
            'data' => $service->getSubtypeByTypeId($typeId),
        ]);
    }
}
