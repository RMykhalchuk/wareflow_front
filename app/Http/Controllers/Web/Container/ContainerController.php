<?php

namespace App\Http\Controllers\Web\Container;


use App\Enums\Containers\ContainerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Container\ContainerCreateUpdateRequest;
use App\Http\Resources\Web\ContainerResource;
use App\Models\Entities\Container\Container;
use App\Models\Entities\Container\ContainerType;
use App\Services\Web\Container\ContainerServiceInterface;
use App\Tables\Container\TableFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * ContainerController.
 */
final class ContainerController extends Controller
{
    public function __construct()
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
     * @return View
     */
    public function index(): View
    {
        $container = Container::all();

        return view('container.index', compact('container'));
    }

    /**
     * @return mixed
     */
    public function filter(): mixed
    {
        return TableFacade::getFilteredData();
    }

    /**
     * @param ContainerCreateUpdateRequest $request
     * @return JsonResponse
     */
    public function store(ContainerCreateUpdateRequest $request): JsonResponse
    {
        $data = $request->except(['_token']);
        $containerId = Container::create($data);

        return response()->json(['container_id' => $containerId]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $containerTypes = ContainerType::all();

        return view('container.create', compact( 'containerTypes'));
    }

    /**
     * @param Container $container
     * @return View
     */
    public function edit(Container $container): View
    {
        $containerTypes = ContainerType::all();

        return view('container.edit', compact('container',  'containerTypes'));
    }

    /**
     * @param ContainerCreateUpdateRequest $request
     * @param Container $container
     * @return JsonResponse
     */
    public function update(ContainerCreateUpdateRequest $request, Container $container): JsonResponse
    {
        $container->fill($request->except(['_token']));
        $container->save();

        return response()->json(['container_id' => $container->id]);
    }

    /**
     * @param Container $container
     * @return View
     */
    public function show(Container $container): View
    {
        $container->load( 'type');

        return view('container.full-info', compact('container'));
    }

    /**
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function getContainersByType($id): AnonymousResourceCollection
    {
        $container = Container::where('type_id', $id)->get();

        return ContainerResource::collection($container);
    }

    /**
     * @param Container $container
     * @return AnonymousResourceCollection
     */
    public function getAllData(Container $container):  AnonymousResourceCollection
    {
        return ContainerResource::collection($container);
    }

    /**
     * Get containers with leftovers.
     *
     * Returns all container registers where leftovers (non-deleted) exist in the given cell.
     *
     * @operationId ContainersWithLeftoversByCell
     *
     * @response array{
     *     total: int,
     *     data: list<array{
     *         id: string,
     *         container_id?: string,
     *         created_at: string|null,
     *         updated_at: string|null,
     *         leftovers_count: int,
     *     }>
     * }
     */
    #[Get(
        summary: 'Containers with leftovers in a cell',
        description: 'Returns container registers that contain leftovers stored in a specific warehouse cell.',
        tags: ['Containers']
    )]
    #[PathParameter('cell', description: 'Cell UUID', type: 'string', format: 'uuid')]
    #[Response(200, description: 'Containers that have leftovers in the given cell')]
    public function withLeftoversByCell(string $cell, ContainerServiceInterface $service): JsonResponse
    {
        return response()->json($service->getWithLeftoversByCell($cell));
    }
}
