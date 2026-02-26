<?php

namespace App\Http\Controllers\Terminal;

use App\Http\Controllers\Api\Get;
use App\Http\Controllers\Api\PathParameter;
use App\Http\Controllers\Api\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Terminal\Leftovers\CellContentsResource;
use App\Http\Resources\Terminal\Leftovers\LeftoverByCellResource;
use App\Http\Resources\Terminal\Leftovers\LeftoverByContainerResource;
use App\Http\Resources\Terminal\Leftovers\LeftoverByProductResource;
use App\Models\Entities\Goods\Goods;
use App\Services\Terminal\Leftovers\LeftoverServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class LeftoverController extends Controller
{
    public function __construct(private LeftoverServiceInterface $leftoverService) {}

    /**
     * Get Leftover list by product name or barcode
     * @urlParam  Get param query - product name or barcode
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate(['query' => 'required|string|min:2']);
        return response()->json(
            ['products' => $this->leftoverService->findLeftoverByProduct($data['query'])]
        );
    }


    /**
     * Get Leftover list by product id
     * @urlParam $goodsId
     */
    public function show(Goods $goods): LeftoverByProductResource
    {
        [$goods, $leftovers] = $this->leftoverService->getLeftoverByProduct($goods);
        return new LeftoverByProductResource($goods, $leftovers);
    }


    /**
     * Get Cell list by cell code
     * @urlParam query - cell code
     */
    public function search(Request $request): JsonResponse
    {
        $data = $request->validate(['query' => 'required|string|min:1']);
        return response()->json(
            ['cells' => $this->leftoverService->findLeftoverByCell(trim($data['query']))]
        );
    }

    /**
     * Get container list by container code
     * @urlParam query - container code
     */
    public function searchContainer(Request $request): JsonResponse
    {
        $data = $request->validate(['query' => 'required|string|min:1']);
        return response()->json(
            ['containers' => $this->leftoverService->findLeftoverByContainer(trim($data['query']))]
        );
    }

    /**
     * Get Leftovers by Container id
     * @urlParam query - cell code
     */
    public function containerLeftovers(string $containerId) : LeftoverByContainerResource
    {
        [$container, $leftovers] = $this->leftoverService->getLeftoverByContainer($containerId);
        return new LeftoverByContainerResource($container, $leftovers);
    }


    /**
     * Get Cell Contents by goods id and cell id
     * @urlParam $cellId - cell id
     */

    public function contents(Goods $goods, string $cellId): CellContentsResource
    {
        [$cell, $leftovers] = $this->leftoverService->getCellContents($cellId, $goods);
        return new CellContentsResource($cell, $leftovers);
    }

    /**
     * Get Leftovers by cell id
     * @urlParam $cellId - cell id
     */
    public function leftovers(string $cellId): LeftoverByCellResource
    {
        [$cell, $leftovers] = $this->leftoverService->getLeftoverByCell($cellId);
        return new LeftoverByCellResource($cell, $leftovers);
    }

    /**
     * Get leftovers by cell.
     *
     * Returns cell meta (status, tasks, last inventory timestamps) and leftovers list for given cell.
     *
     * @operationId LeftoversByCell
     *
     * @response array{
     *     data: array{
     *         cell_id: string,
     *         cell_code: string|null,
     *         loaded_weight: float|int,
     *         has_task: bool,
     *         status_code: int|null,
     *         status_label: string|null,
     *         blocked_by_inventory_id: string|null,
     *         last_invent: array{
     *             from_inventory_leftovers: string|null,
     *             from_manual_logs: string|null,
     *             last_at: string|null,
     *         },
     *         leftovers: list<array{
     *             leftover_id: string,
     *             goods_id: string,
     *             goods_name: string|null,
     *             barcode: string|null,
     *             qty: float|int|null,
     *             batch: string|null,
     *             expires_at: string|null,
     *             created_date: string|null,
     *             measurement_unit: array{
     *                 code: string|null,
     *                 name: string|null,
     *                 short_name: string|null,
     *                 id: string|null,
     *             },
     *             package: array{
     *                 id: string|null,
     *                 name: string|null,
     *                 units_qty: float|int|null,
     *             },
     *             goods_packages: list<array{
     *                 id: string|null,
     *                 name: string|null,
     *                 short_name: string|null,
     *                 ratio: float|int|null,
     *                 weight: float|int|null,
     *                 volume: float|int|null,
     *             }>
     *         }>
     *     }
     * }
     */
    #[Get(
        summary: 'Get leftovers by cell',
        description: 'Returns cell meta (status, last inventory timestamps) and leftovers list for given cell.',
        tags: ['Leftovers']
    )]
    #[PathParameter('cell', description: 'Cell UUID', type: 'string')]
    #[Response(200, description: 'Cell leftovers data')]
    public function byCell(string $cell): JsonResponse
    {
        $result = $this->leftoverService->findLeftoversByCell($cell);

        return response()->json(['data' => $result]);
    }
}
