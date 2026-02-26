<?php

namespace App\Http\Resources\Terminal\Income\Manual;

use App\Enums\Task\TaskStatus;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Goods\Goods;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $document = $this->resource;

        $skuTable = collect($document->data()['sku_table'] ?? [])
            ->keyBy('id');

        $incomeLeftovers = IncomeDocumentLeftover::with(['package', 'container'])
            ->where('document_id', $document->id)
            ->get();

        $leftoverData = [];

        foreach ($incomeLeftovers as $leftover) {
            $goodsId = $leftover->goods_id;

            if (!isset($leftoverData[$goodsId])) {
                $skuRow = $skuTable[$goodsId] ?? null;

                $goods = Goods::with(['barcodes', 'measurement_unit:id,name,key'])->findOrFail($goodsId);

                $leftoverData[$goodsId] = [
                    'document_id'    => $document->id,
                    'goods_id'       => $goodsId,
                    'name'           => $goods->name,
                    'barcode'        => $goods->barcodes->first()->barcode,
                    'measurement_unit' => [
                        'name' => $goods->measurement_unit->name,
                        'key'  => $goods->measurement_unit->key,
                    ],
                    'total_quantity' => 0,
                    'containers'     => [],
                ];
            }

            $quantityInMainUnits = round($leftover->package->main_units_number * $leftover->quantity, 3);
            $leftoverData[$goodsId]['total_quantity'] += $quantityInMainUnits;

            if ($leftover->container_id) {
                $containerId = $leftover->container_id;

                if (!isset($leftoverData[$goodsId]['containers'][$containerId])) {
                    $leftoverData[$goodsId]['containers'][$containerId] = [
                        'container_id' => $containerId,
                        'code'         => $leftover->container->code ?? '',
                        'quantity'     => 0,
                    ];
                }

                $leftoverData[$goodsId]['containers'][$containerId]['quantity'] += $quantityInMainUnits;
            }
        }

        $task = $document->tasks()->where('kind', 'on_arrival')->first();
        $isArchived = $task?->status === TaskStatus::DONE;
        $taskId = $task?->local_id;

        return [
            'is_archived' => $isArchived,
            'task_id'     => $taskId,
            'products'    => array_values(
                array_map(function ($item) {
                    $item['containers'] = array_values($item['containers']);
                    $item['total_quantity'] = round($item['total_quantity'], 3);
                    return $item;
                }, $leftoverData)
            ),
        ];
    }
}
