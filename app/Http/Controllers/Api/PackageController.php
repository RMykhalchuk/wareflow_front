<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Goods\PackageRequest;
use App\Http\Requests\Api\Package\PackageServiceInterface;
use App\Http\Resources\Api\PackageResource;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Packages
 **/
class PackageController extends Controller
{
    public function __construct(private readonly PackageServiceInterface $packageService) {}

    /**
     * Show package
     */
    public function show(Goods $goods, Package $package)
    {
        return PackageResource::make($package);
    }

    /**
     * Add package to goods
     */
    public function store(PackageRequest $request, Goods $goods): JsonResponse
    {
        $package = $this->packageService->store($request->validated(), $goods);

        return response()->json(
            [
                'message' => 'Created',
                'data' => $package
            ], 201);
    }

    /**
     * Update package in goods
     */

    public function update(PackageRequest $request, Goods $goods, Package $package): JsonResponse
    {
        $updated = $this->packageService->update($request->validated(), $goods, $package);

        return response()->json(
            [
                'message' => 'Updated',
                'data' => $updated
            ]);
    }

    /**
     * Delete package in goods
     */
    public function destroy(Goods $goods, Package $package): JsonResponse
    {
        $this->packageService->delete($goods, $package);

        return response()->json(status: 204);
    }
}
