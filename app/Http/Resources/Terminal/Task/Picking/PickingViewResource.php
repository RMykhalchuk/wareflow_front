<?php

namespace App\Http\Resources\Terminal\Task\Picking;

use App\Enums\Task\TaskProcessingType;
use App\Models\Entities\Document\OutcomeDocumentLeftover;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PickingViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = $this->products();

        if (empty($products)) {
            return [];
        }

        $productIds = array_column($products, 'id');

        $goodsMap = Goods::with('measurement_unit')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $leftoversMap = Leftover::with(['package', 'cell', 'goods.measurement_unit', 'container'])
            ->whereIn('goods_id', $productIds)
            ->get()
            ->groupBy('goods_id');

        $outcomeLogsMap = OutcomeDocumentLeftover::with('leftover.package')
            ->where('processing_type', TaskProcessingType::PICKING->value)
            ->where('document_id', $this->id)
            ->whereHas('leftover', function ($q) use ($productIds) {
                $q->whereIn('goods_id', $productIds);
            })
            ->get()
            ->groupBy(fn ($item) => $item->leftover->goods_id);

        $countProductInCell = [];

        foreach ($products as $product) {
            $productId = $product['id'];

            $goods = $goodsMap[$productId] ?? null;

            if ($goods === null) {
                continue;
            }

            $countProductInCell[$productId] = [
                'quantity' => [
                    'total' => $product['quantity'],
                    'current' => 0,
                ],
                'measurement_unit' => $goods->measurement_unit->toArray(),
                'barcode' => $goods->getMainPackageBarcode(),
                'leftovers' => [],
            ];

            foreach ($leftoversMap[$productId] ?? [] as $leftover) {
                $quantity = $leftover->quantity * $leftover->package->main_units_number;

                if (array_key_exists($leftover->id, $countProductInCell[$productId]['leftovers'])) {
                    $countProductInCell[$productId]['leftovers'][$leftover->id]['quantity'] += $quantity;
                } else {
                    $countProductInCell[$productId]['leftovers'][$leftover->id] = [
                        'cell_info' => $leftover->cell->getAllocation,
                        'cell_id' => $leftover->cell_id,
                        'container_id' => $leftover->container?->id,
                        'container_code' => $leftover->container?->code,
                        'quantity' => $quantity,
                    ];
                }
            }

            foreach ($outcomeLogsMap[$productId] ?? [] as $outcomeLeftoverLog) {
                $countProductInCell[$productId]['quantity']['current'] +=
                    $outcomeLeftoverLog->quantity * $outcomeLeftoverLog->leftover->package->main_units_number;
            }
        }

        return $countProductInCell;
    }
}
