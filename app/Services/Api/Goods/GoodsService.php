<?php

namespace App\Services\Api\Goods;

use App\Models\Entities\Goods\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GoodsService extends \App\Services\Web\Goods\GoodsService implements GoodsServiceInterface
{
    public function paginate(Request $request)
    {
        $perPage = (int)$request->query('per_page', 15);
        $search = $request->query('search');

        $goodsQuery = Goods::where('is_kit', false);

        if ($search) {
            $goodsQuery->where('name', 'ILIKE', "%{$search}%");
        }

        return $goodsQuery->simplePaginate($perPage);
    }
}
