<?php

namespace App\Services\Web\Leftover;

use App\Models\Entities\Document\Document;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use App\Models\Entities\WarehouseComponents\Cell;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LeftoverService implements LeftoverServiceInterface
{
    public function addByDocument(Request $request, Document $document): array
    {
        if (!$document->goods->count()) {
            return [
                'message' => 'До документу не прикріплено товарів для приходу',
                'status' => 422,
            ];
        }

        $warehouseFieldName = '6select_field_6';
        $docData = json_decode($document->data, true);
        $warehouseId = intval($docData['header_ids']["{$warehouseFieldName}_id"]);

        foreach ($document->goods as $goods) {
            Leftover::create([
                                 'goods_id' => $goods->id,
                                 'quantity' => $goods->pivot->count,
                                 'warehouse_id' => $warehouseId,
                             ]);
        }

        return ['message' => 'Add successful', 'status' => 200];
    }

    public function removeByDocument(Request $request, Document $document): array
    {
        if (!$document->goods->count()) {
            return [
                'message' => 'До документу не прикріплено товарів для списання',
                'status' => 422,
            ];
        }

        $warehouseFieldName = '2select_field_2';
        $docData = json_decode($document->data, true);
        $warehouseId = intval($docData['header_ids']["{$warehouseFieldName}_id"]);
        $warehouseLeftovers = Leftover::where('warehouse_id', $warehouseId)->get();

        foreach ($document->goods as $goods) {
            $consignment = json_decode($goods->pivot->data, true)['consignment'];
            if ($warehouseLeftovers->where('goods_id', $goods->id)->where('consignment', $consignment)->sum('count') < $goods->pivot->count) {
                return [
                    'message' => "Не вистачає товару {$goods->name} на складі для списання",
                    'status' => 422,
                ];
            }
        }

        foreach ($document->goods as $goods) {
            $consignment = json_decode($goods->pivot->data, true)['consignment'];
            Leftover::create([
                                 'goods_id' => $goods->id,
                                 'document_id' => $document->id,
                                 'count' => -$goods->pivot->count,
                                 'consignment' => $consignment,
                                 'warehouse_id' => $warehouseId,
                             ]);
        }

        return ['message' => 'Remove successful', 'status' => 200];
    }

    public function moveByDocument(Request $request, Document $document): array
    {
        if (!$document->goods->count()) {
            return [
                'message' => 'До документу не прикріплено товарів для переміщення',
                'status' => 422,
            ];
        }

        $warehouseFromFieldName = '2select_field_2';
        $warehouseToFieldName = '3select_field_3';
        $docData = json_decode($document->data, true);
        $warehouseFromId = intval($docData['header_ids']["{$warehouseFromFieldName}_id"]);
        $warehouseToId = intval($docData['header_ids']["{$warehouseToFieldName}_id"]);
        $warehouseLeftovers = Leftover::where('warehouse_id', $warehouseFromId)->get();

        foreach ($document->goods as $goods) {
            $consignment = json_decode($goods->pivot->data, true)['6date_field_6'];
            if ($warehouseLeftovers->where('goods_id', $goods->id)->where('consignment', $consignment)->sum('count') < $goods->pivot->count) {
                return [
                    'message' => "Не вистачає товару {$goods->name} на складі для переміщення",
                    'status' => 422,
                ];
            }
        }

        foreach ($document->goods as $goods) {
            $consignment = json_decode($goods->pivot->data, true)['consignment'];
            Leftover::create([
                                 'goods_id' => $goods->id,
                                 'document_id' => $document->id,
                                 'count' => -$goods->pivot->count,
                                 'consignment' => $consignment,
                                 'warehouse_id' => $warehouseFromId,
                             ]);
            Leftover::create([
                                 'goods_id' => $goods->id,
                                 'document_id' => $document->id,
                                 'count' => $goods->pivot->count,
                                 'consignment' => $consignment,
                                 'warehouse_id' => $warehouseToId,
                             ]);
        }

        return ['message' => 'Move successful', 'status' => 200];
    }

    public function calculatePackage(Leftover $leftover)
    {
        $current = Package::find($leftover->package_id);
        if (!$current) {
            return collect();
        }

        $chain = collect([$current]);

        // рекурсивно додаємо дочірні пакування
        $this->appendChildren($chain, $current);

        return $chain;
    }

    private function appendChildren(Collection &$chain, Package $package): void
    {
        $child = Package::where('parent_id', $package->id)->first();
        if ($child) {
            $chain->push($child);
            $this->appendChildren($chain, $child);
        }
    }

    /**
     * Enriches the values of the items in the 'warehouse_id' collection based on the 'cell_id'.
     *
     * @param Collection $items The collection of items to create (can print 'cell_id').
     * @return Collection The enriched collection.
     * @throws Exception If cell_id is not found.
     */

    public function enrichWithWarehouseIds(Collection $items): Collection
    {
        if ($items->isEmpty()) {
            return $items;
        }

        // get all cell_id
        $cellIds = $items->pluck('cell_id')->unique()->all();

        // [cell_id => warehouse_id]
        $cellWarehouseMap = Cell::getWarehouseMapByCellIds($cellIds);

        //  mapping items with  new warehouse_id
        return $items->map(function ($item) use ($cellWarehouseMap) {
            $warehouseId = $cellWarehouseMap[$item['cell_id']] ?? null;

            $item['warehouse_id'] = $warehouseId;
            return $item;
        });
    }
}
