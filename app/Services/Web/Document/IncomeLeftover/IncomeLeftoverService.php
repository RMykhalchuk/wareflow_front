<?php

namespace App\Services\Web\Document\IncomeLeftover;

use App\Http\Requests\Web\Document\AbstractLeftoverRequest;
use App\Models\Entities\Document\Document;
use App\Models\Entities\Document\IncomeDocumentLeftover;

class IncomeLeftoverService implements IncomeLeftoverInterface
{
    public function store(AbstractLeftoverRequest $request, Document $document, string $goodsId): array
    {
        $leftover = $request->validated();

        $lastLeftover = IncomeDocumentLeftover::where('document_id', $document->id)
            ->where('goods_id', $goodsId)
            ->orderByDesc('table_id')
            ->first();

        $tableId = $lastLeftover ? $lastLeftover->table_id + 1 : 1;

        $incomeLeftover = IncomeDocumentLeftover::create(
            [
                'table_id' => $tableId,
                'batch' => $leftover['batch'],
                'has_condition' => $leftover['has_condition'] ?? false,
                'manufacture_date' => $leftover['manufacture_date'],
                'expiration_term' => $leftover['expiration_term'],
                'bb_date' => $leftover['bb_date'],
                'package_id' => $leftover['package_id'],
                'container_id' => $leftover['container_id'] ?? null,
                'quantity' => $leftover['quantity'],
                'document_id' => $document->id,
                'goods_id' => $goodsId,
                'creator_id' => auth()->id(),
            ]);

        return $incomeLeftover->toArray();
    }

    public function update(AbstractLeftoverRequest $request, IncomeDocumentLeftover $incomeDocumentLeftover): array
    {
        $leftover = $request->validated();

        $incomeDocumentLeftover->update($leftover);

        return $incomeDocumentLeftover->toArray();
    }

    public function destroy(IncomeDocumentLeftover $incomeDocumentLeftover): bool
    {
        return $incomeDocumentLeftover->delete() > 0;
    }

    public function getAllByDocumentAndUnique(Document $document, string $goodsId): array
    {
        $leftovers = IncomeDocumentLeftover::query()
            ->with([
                       'user:id,surname,name,patronymic',
                       'goods.measurement_unit:id,name',
                       'container:id,code',
                       'package:id,name,main_units_number',
                   ])
            ->where('document_id', $document->id)
            ->where('goods_id', $goodsId)
            ->orderBy('table_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'table_id' => $item->table_id,
                    'quantity' => $item->quantity,
                    'batch' => $item->batch,
                    'manufacture_date' => $item->manufacture_date,
                    'bb_date' => $item->bb_date,
                    'expiration_term' => $item->expiration_term,
                    'has_condition' => $item->has_condition,
                    'container' => [
                        'id' => $item->container->id ?? null,
                        'code' => $item->container->code ?? null,
                    ],
                    'package' => [
                        'id' => $item->package->id,
                        'name' => $item->package->name,
                        'main_units_number' => $item->package->main_units_number,
                        'measurement_unit_count' => isset($item->package->main_units_number, $item->quantity)
                            ? $item->package->main_units_number * $item->quantity
                            : null,
                    ],
                    'measurement_unit' => [
                        'name' => $item->goods->measurement_unit->name ?? null,
                    ],
                    'user' => [
                        'surname' => $item->user->surname ?? null,
                        'name' => $item->user->name ?? null,
                        'patronymic' => $item->user->patronymic ?? null,
                    ],
                ];
            })
            ->toArray();

        return $leftovers;
    }
}
