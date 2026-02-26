<?php

namespace App\Http\Requests\Api\Package;

use App\Http\Requests\Api\Goods\PackageRequest;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Package;
use Illuminate\Http\Request;

interface PackageServiceInterface
{
    public function delete(Goods $goods, Package $package);

    public function store(array $data, Goods $goods): Package;

    public function update(array $data, Goods $goods, Package $package) : Package;
}
