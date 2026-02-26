<?php

namespace App\Http\Resources\Terminal\Income;

use App\Enums\Task\TaskStatus;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Goods\Goods;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = $this->data()['sku_table'] ?? [];
        $task = $this->tasks()->where('kind', '')->first();
        $isArchived = $task?->status === TaskStatus::DONE;
        $taskId = $task?->local_id;

        if (empty($products)) {
            return [
                'is_archived' => $isArchived,
                'task_id'     => $taskId,
                'products'    => [],
            ];
        }

        $goodsIds = collect($products)->pluck('id')->unique()->all();
        $goodsList = Goods::with(['barcodes', 'measurement_unit:id,name,key'])
            ->whereIn('id', $goodsIds)
            ->get()
            ->keyBy('id');

        $data = [];

        foreach ($products as $product) {
            $productId = $product['id'];
            $goods = $goodsList[$productId] ?? null;

            $containers = [];
            $progressCurrent = 0;
            $progressTotal = $product['quantity'] ?? 0;
            $isProcessed = $product['processed'] ?? false;

            $leftovers = IncomeDocumentLeftover::with(['package', 'container'])
                ->where('document_id', $this->id)
                ->where('goods_id', $productId)
                ->get();

            foreach ($leftovers as $leftover) {
                $quantity = $leftover->quantity * $leftover->package->main_units_number;
                $progressCurrent += $quantity;

                if ($leftover->container_id) {
                    $containerId = $leftover->container_id;

                    if (!isset($containers[$containerId])) {
                        $containers[$containerId] = [
                            'code' => $leftover->container->code,
                            'quantity' => 0,
                        ];
                    }

                    $containers[$containerId]['quantity'] += $quantity;
                }
            }

            $progressCurrent = round($progressCurrent, 3);
            $progressTotal = round((float) $progressTotal, 3);
            $lack = $isProcessed ? 0 : round(max(0, $progressTotal - $progressCurrent), 3);

            $data[] = [
                'barcode' => $goods?->barcodes->first()?->barcode,
                'progress' => [
                    'current' => $progressCurrent,
                    'total' => $progressTotal,
                ],
                'containers' => array_values($containers),
                'product' => [
                    'id' => $productId,
                    'name' => $goods?->name ?? ($product['name'] ?? ''),
                ],
                'measurement_unit' => [
                    'name' => $goods?->measurement_unit?->name ?? '',
                    'key'  => $goods?->measurement_unit?->key ?? null,
                ],
                'lack' => $lack,
            ];
        }

        return [
            'is_archived' => $isArchived,
            'task_id'     => $taskId,
            'products'    => $data,
        ];
    }

}
