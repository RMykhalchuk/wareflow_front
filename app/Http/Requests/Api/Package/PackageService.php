<?php

namespace App\Http\Requests\Api\Package;

use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Package;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PackageService implements PackageServiceInterface
{
    public function delete(Goods $goods, Package $package): void
    {
        if (!$package->canEdit) {
            throw new HttpException(403, 'Not allowed');
        }

        if ($goods->id !== $package->goods_id) {
            throw new HttpException(403, 'This packaging does not belong to this product');
        }

        $package->delete();
    }

    public function store(array $data, Goods $goods): Package
    {
        // parent_id exists?
        if (!empty($data['parent_id'])) {
            $parent = Package::where('local_id', $data['parent_id'])->firstOrFail();

            // parent must belong to the same goods
            if ($parent->goods_id !== $goods->id) {
                throw new HttpException(422, 'Parent package belongs to another product');
            }
        }

        $data['goods_id'] = $goods->id;

        return Package::create($data);
    }

    public function update(array $data, Goods $goods, Package $package): Package
    {
        if (!$package->canEdit) {
            throw new HttpException(403, 'Not allowed');
        }

        if ($goods->id !== $package->goods_id) {
            throw new HttpException(403, 'This packaging does not belong to this product');
        }

        // Parent validation only if changed
        if (!empty($data['parent_id']) && $data['parent_id'] != $package->parent_id) {
            $parent = Package::where('local_id', $data['parent_id'])->firstOrFail();

            if ($parent->goods_id !== $goods->id) {
                throw new HttpException(422, 'Parent package belongs to another product');
            }
        }

        $package->update($data);

        return $package;
    }
}
