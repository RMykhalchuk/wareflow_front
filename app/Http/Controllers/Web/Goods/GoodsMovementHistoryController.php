<?php

namespace App\Http\Controllers\Web\Goods;

use App\Http\Controllers\Controller;
use App\Models\Entities\Goods\Goods;
use App\Services\Web\Goods\GoodsMovementHistoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GoodsMovementHistoryController extends Controller
{
    public function __construct(
        private readonly GoodsMovementHistoryService $service
    ) {
        $this->middleware('can:view-dictionaries');
    }

    public function filter(Request $request, Goods $sku): JsonResponse
    {
        $page    = max(1, (int) $request->query('pagenum', 0) + 1);
        $perPage = max(1, (int) $request->query('pagesize', 15));

        return response()->json(
            $this->service->getHistory($sku->id, $page, $perPage)
        );
    }
}