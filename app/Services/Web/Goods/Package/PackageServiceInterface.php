<?php

namespace App\Services\Web\Goods\Package;


use Illuminate\Support\Collection;

interface PackageServiceInterface
{
    public function store(array $packagesData, string $goodsId): Collection;

    public function update(array $packagesData, string $goodsId): Collection;

}
