<?php

namespace App\Services\Api\Goods;

use App\Models\Entities\Goods\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface GoodsServiceInterface
{
    public function paginate(Request $request);
}
