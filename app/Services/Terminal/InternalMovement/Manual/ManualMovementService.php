<?php

namespace App\Services\Terminal\InternalMovement\Manual;

use App\Http\Requests\Terminal\MoveLeftoversOrContainerRequest;
use App\Http\Resources\Terminal\ManualMovement\ContainerResource;
use App\Http\Resources\Terminal\ManualMovement\LeftoverResource;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Services\Web\Leftover\Package\UnpackageService;

class ManualMovementService implements ManualMovementServiceInterface
{
    public function scanContainer(ContainerRegister $container)
    {
        $leftovers = Leftover::with(['container', 'goods.barcodes', 'goods.measurement_unit', 'package.barcode'])
            ->where('container_id', $container->id)
            ->get();

        $container->loadCount('leftovers')->load('cell');

        return [
            'container' => new ContainerResource($container),
            'leftovers' => LeftoverResource::collection($leftovers),
        ];
    }

    public function scanCell(string $cellId)
    {
        $leftovers = Leftover::with(['container', 'goods.barcodes', 'goods.measurement_unit', 'package.barcode'])
            ->where('cell_id', $cellId)
            ->where('container_id', null)
            ->get();

        $cell = Cell::inCurrentWarehouse()->select('code')->findOrFail($cellId);

        $containers = ContainerRegister::inCurrentWarehouse()->with('leftovers')->where('cell_id', $cellId)->get();

        return [
            'containers' => ContainerResource::collection($containers),
            'leftovers' => LeftoverResource::collection($leftovers),
            'cell' => ['id' => $cellId, 'code' => $cell->code],
        ];
    }


    public function move(MoveLeftoversOrContainerRequest $request): void
    {
        if ($request->filled('cell_id')) {
            Cell::findOrFail($request->cell_id);
            $this->moveToCell($request);
        }

        if ($request->filled('container_id')) {
            ContainerRegister::findOrFail($request->container_id);
            $this->moveToContainer($request);
        }
    }

    private function moveToCell(MoveLeftoversOrContainerRequest $request): void
    {
        // --- Move individual leftovers ---
        collect($request->input('leftovers', []))->each(function ($leftover) use ($request) {
            UnpackageService::unpackage(
                $leftover['id'],
                $leftover['package_id'] ?? null,
                $leftover['quantity'] ?? 0,
                $request->cell_id,
                $request->container_id
            );
        });

        // --- Move containers with all their leftovers ---
        collect($request->input('containers', []))->each(function ($containerId) use ($request) {
            ContainerRegister::inCurrentWarehouse()->where('id', $containerId)
                ->update(['cell_id' => $request->cell_id]);

            Leftover::inCurrentWarehouse()->where('container_id', $containerId)
                ->update(['cell_id' => $request->cell_id]);
        });
    }

    private function moveToContainer(MoveLeftoversOrContainerRequest $request): void
    {
        $container = ContainerRegister::where(fn($q) => $q->inCurrentWarehouse()->orWhereNull('cell_id'))
            ->select('id', 'cell_id')
            ->findOrFail($request->container_id);

        $leftoverIds = collect($request->input('leftovers', []))
            ->pluck('id')
            ->filter()
            ->toArray();

        if (empty($leftoverIds)) {
            return;
        }

        Leftover::whereIn('id', $leftoverIds)
            ->update([
                         'container_id' => $container->id,
                         'cell_id' => $container->cell_id,
                     ]);
    }



    public function getContainerAndCell(string $query)
    {
        $query = mb_strtolower($query);

        $containers = ContainerRegister::where(function ($q) {
            $q->inCurrentWarehouse()
                ->orWhereNull('cell_id');
        })->whereRaw('LOWER(code) LIKE ?', ["%{$query}%"])
            ->get(['id', 'code']);

        $cells = Cell::inCurrentWarehouse()->whereRaw('LOWER(code) LIKE ?', ["%{$query}%"])
            ->get(['id', 'code'])
            ->each->setAppends([]);

        return [
            'containers' => $containers,
            'cells' => $cells,
        ];
    }

}
