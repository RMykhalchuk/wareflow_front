<?php

namespace App\Http\Controllers\Web\Goods;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Goods\PackageRequest;
use App\Models\Entities\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class PackageController extends Controller
{
    /**
     * Display a listing of packages.
     */
    public function index(): JsonResponse
    {
        $packages = Package::all();
        return response()->json($packages);
    }


    /**
     * Display the specified package.
     */
    public function show(Package $package): JsonResponse
    {
        return response()->json($package);
    }

    /**
     * Update the specified package in storage.
     */
    public function update(PackageRequest $request, Package $package): JsonResponse
    {
        $package->update($request->validated());
        return response()->json(['message' => 'Package updated', 'package' => $package]);
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(Package $package): JsonResponse
    {
        $package->delete();
        return response()->json(['message' => 'Package deleted']);
    }

    public function packageList(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);

        $packages = Package::query()
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json($packages);
    }
}
