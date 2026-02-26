<?php

namespace App\Http\Resources\Terminal\Income\Manual;

use App\Enums\Task\TaskStatus;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $document = $this['document'];
        $goods = $this['goods'];

        $incomeLeftovers = IncomeDocumentLeftover::with(
            [
                'package.type',
                'goods.measurement_unit:id,name,key',
                'goods.barcodes',
                'container',
                'user',
            ])
            ->where('document_id', $document->id)
            ->where('goods_id', $goods->id)
            ->get();

        $task = $document->tasks()->where('kind', 'on_arrival')->first();
        $isArchived = $task?->status === TaskStatus::DONE;

        $leftoverData = [
            'is_archived'   => $isArchived,
            'task_number'   => $task?->local_id,
            'total_quantity' => 0,
            'leftovers'     => [],
        ];

        foreach ($incomeLeftovers as $leftover) {
            // Обчислюємо кількість в основних одиницях
            $quantityInMainUnits = round($leftover->package->main_units_number * $leftover->quantity, 3);

            $leftoverData['leftovers'][] = [
                'id' => $leftover->id,
                'batch' => $leftover->batch,
                'package' => $leftover->package->toArray(),
                'quantity' => $leftover->quantity,
                'quantity_in_main_units' => $quantityInMainUnits,
                'manufacture_date'  => $leftover->manufacture_date,
                'bb_date' => $leftover->bb_date,
                'expiration_term' => $leftover->expiration_term,
                'container' => $leftover->container?->toArray(),
                'executor' => $leftover->user?->getInitials(),
            ];

            // Додаємо до загальної кількості
            $leftoverData['total_quantity'] += $quantityInMainUnits;
        }

        $leftoverData['total_quantity'] = round($leftoverData['total_quantity'], 3);

        return $leftoverData;
    }
}
