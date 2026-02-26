<?php

namespace App\Services\Web\Goods;

use App\Models\Entities\Goods\Goods;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface GoodsServiceInterface
{
    /**
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function list(array $params): LengthAwarePaginator;

    public function getAllGoods(): array;

    public function prepareCreateData(): array;

    public function prepareEditData(Goods $sku): array;

    public function prepareShowData(Goods $sku): array;

    public function getSkuByCategory(int $id): AnonymousResourceCollection;
}
