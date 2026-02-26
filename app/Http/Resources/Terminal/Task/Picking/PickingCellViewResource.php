<?php

namespace App\Http\Resources\Terminal\Task\Picking;

use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Terminal\TerminalLeftoverLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PickingCellViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $document = $this['document'];

        $cell = $this['cell'];

        $goods = $this['goods'];

        $container = $this['container'];


        $products = $document->products();

        if (empty($products)) {
            return [];
        }

        $dataArray = [];


        foreach ($products as $product) {
            $productId = $product['id'];

            if ($product['id'] !== $goods->id) {
                continue;
            }

            $dataArray = [
                'quantity' => [
                    'total' => $product['quantity'],
                    'current' => 0,
                ],
                'measurement_unit' => $goods->measurement_unit->toArray(),
                'barcode' => $goods->getMainPackageBarcode(),
                'leftovers' => [],
            ];

            $leftoversQuery = Leftover::with(['package', 'cell', 'goods.measurement_unit', 'container'])
                ->where('goods_id', $productId)
                ->where('cell_id', $cell->id);

            if ($container !== null) {
                $leftoversQuery->where('container_id', $container);
            }

            $leftovers = $leftoversQuery->get();

            foreach ($leftovers as $leftover) {
                $quantity = $leftover->quantity * $leftover->package->main_units_number;

                if (array_key_exists($leftover->id, $dataArray['leftovers'])) {
                    $dataArray['leftovers'][$leftover->id]['quantity'] += $quantity;
                } else {
                    $dataArray['leftovers'][$leftover->id] = [
                        'cell_info' => $leftover->cell->getAllocation,
                        'cell_id' => $leftover->cell_id,
                        'quantity' => $quantity,
                        'container_id' => $leftover->container?->id,
                        'container_code' => $leftover->container?->code,
                    ];
                }
            }

            $dataArray['archive'] = [];

            $terminalLeftoverLogs = TerminalLeftoverLog::with(['leftover.cell', 'package.barcode', 'container'])
                ->where('document_id', $document->id)
                ->whereHas('leftover', function ($q) use ($productId) {
                    $q->where('goods_id', $productId);
                })->get();

            foreach ($terminalLeftoverLogs as $terminalLeftoverLog) {
                $dataArray['quantity']['current'] += $terminalLeftoverLog->quantity *
                    $terminalLeftoverLog->package->main_units_number;

                $dataArray['archive'][] = [
                    'quantity' => $terminalLeftoverLog->quantity * $terminalLeftoverLog->package->main_units_number,
                    'barcode' => $terminalLeftoverLog->package->barcode->barcode,
                    'package' => $terminalLeftoverLog->package->name,
                    'package_quantity' => $terminalLeftoverLog->quantity,
                    'cell_from' => $terminalLeftoverLog->leftover->cell->code,
                ];
            }

        }

        return $dataArray;
    }
}
