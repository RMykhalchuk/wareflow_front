<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Location\LocationRequest;
use App\Http\Resources\Web\LocationResource;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Location;
use App\Services\Web\Location\LocationServiceInterface;
use App\Tables\Location\TableFacade;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

/**
 * LocationController.
 */
class LocationController extends Controller
{
    /**
     * @param LocationServiceInterface $locationService
     */
    public function __construct(private LocationServiceInterface $locationService)
    {
        $this->middleware('can:view-dictionaries')->only([
            'index',
            'create',
            'store',
            'show',
            'edit',
            'update',
            'destroy',
            'filter',
        ]);
    }

    /**
     * @return Factory|View
     */
    public function index(): Factory|View
    {
        $locationCount = Location::count();

        return view('location.index', ['goodsCount' => $locationCount]);
    }

    /**
     * @return Factory|View
     */
    public function create(): Factory|View
    {
        $companies = Company::all();

        return view('location.create', compact('companies'));
    }

    /**
     * @param LocationRequest $request
     * @return JsonResponse
     */
    public function store(LocationRequest $request): JsonResponse
    {
        $location = $this->locationService->create($request->validated());

        return response()->json(LocationResource::make($location), 201);
    }

    /**
     * @param Location $location
     * @return Factory|View
     */
    public function show(Location $location): Factory|View
    {
        return view('location.view', compact('location'));
    }

    /**
     * @param Location $location
     * @return Factory|View
     */
    public function edit(Location $location): Factory|View
    {
        return view('location.edit', compact('location'));
    }

    /**
     * @param LocationRequest $request
     * @param Location $location
     * @return JsonResponse
     */
    public function update(LocationRequest $request, Location $location): JsonResponse
    {
        $location = $this->locationService->update($location, $request->validated());

        return response()->json($location);
    }

    /**
     * @param Location $location
     * @return JsonResponse
     */
    public function destroy(Location $location): JsonResponse
    {
        $this->locationService->delete($location);

        return response()->json(null, 204);
    }

    /**
     * @return mixed
     */
    public function filter(): mixed
    {
        return TableFacade::getFilteredData();
    }
}
