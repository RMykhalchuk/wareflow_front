<?php

namespace App\Tables\Inventory;

use App\Models\Entities\Inventory\Inventory;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Collection;

/**
 * FormatTableData.
 */
final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @param $paginator
     * @return array
     */
    #[\Override]
    public function formatData($paginator): array
    {
        /** @var Collection $data */
        $data = collect($paginator->items())->map(function ($i) {
            $startDate = $i->start_date ?? null;
            $endDate   = $i->end_date   ?? null;
            $createdAt = $i->created_at ?? null;

            $kind = $startDate && $endDate
                ? __('localization.inventory.view.kind.planned')
                : __('localization.inventory.view.kind.manual');

            $names = collect($i->performers ?? [])
                ->map(fn($p) => $p->initial())
                ->filter()
                ->unique()
                ->values();

            if ($names->isEmpty() && $i->performer) {
                $initial = $i->performer->initial();
                $names = $initial !== null ? collect([$initial]) : collect();
            }

            $executor = $names->isNotEmpty() ? $names->implode("\r") : null;

            $status = $i->deleted_at ? Inventory::STATUS_CANCELLED : $i->status;

            return [
                'id'        => (string) $i->local_id,
                'real_id'   => (string) $i->id,
                'type'      => (string) $i->type,
                'kind'      => $kind,
                'status_id' => $status,

                'start'     => [
                    'date' => $startDate?->format('Y.m.d'),
                    'time' => $startDate?->format('H:i'),
                ],

                'completion'=> $endDate ? [
                    'type' => 'done',
                    'date' => $endDate->format('Y.m.d'),
                    'time' => $endDate->format('H:i'),
                ] : null,

                'executor'  => $executor,
                'created'   => [
                    'name' => $i->creator?->initial(),
                    'date' => $createdAt?->format('Y.m.d'),
                    'time' => $createdAt?->format('H:i'),
                ],
            ];
        })->values();

        return [
            'total' => (int) $paginator->total(),
            'data'  => $data,
        ];
    }

    /**
     * @param $fieldName
     * @return array|string
     */
    #[\Override]
    public function renameFields($fieldName): array|string
    {
        if ($fieldName === 'status') {
            return 'status';
        }
        if ($fieldName === 'start') {
            return 'start_date';
        }
        if ($fieldName === 'completion') {
            return 'end_date';
        }

        return $fieldName;
    }

    /**
     * @param $relation
     * @return string
     */
    public function relationsSelectByField($relation): string
    {
        return '*';
    }
}
