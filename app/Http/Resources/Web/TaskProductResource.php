<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'task_number' => $this->resource['task_number'] ?? null,
            'is_archived' => $this->resource['is_archived'] ?? false,
            'product' => [
                'id' => $this->resource['product']['id'],
                'name' => $this->resource['product']['name'] ?? null,
                'quantity' => $this->resource['product']['quantity'],
                'measurement_unit' => $this->resource['product']['unit'] ?? null,
            ],
            'progress' => [
                'total'   => rtrim(rtrim(number_format($this->resource['progress']['total'], 3, '.', ''), '0'), '.'),
                'current' => rtrim(rtrim(number_format($this->resource['progress']['current'], 3, '.', ''), '0'), '.'),
            ],
            'executors' => $this->resource['executors'],
            'dictionary' => [
                'packages' => $this->resource['dictionary']['packages'],
                'containers' => $this->resource['dictionary']['containers'],
                'expiration_date' => $this->resource['dictionary']['expiration_date'] ?? null,
            ],
            'leftovers' => $this->resource['leftovers']->map(function ($l) {
                $mainUnitsNumber = $l->package->main_units_number * $l->quantity;

                $mainUnitsNumber = rtrim(
                    rtrim(number_format($mainUnitsNumber, 3, '.', ''), '0'),
                    '.'
                );

                return [
                    'id' => $l->id,
                    'batch' => $l->batch,
                    'quantity' => $l->quantity,
                    'main_units_number' => $mainUnitsNumber,
                    'package' => [
                        'id' => $l->package->id,
                        'name' => $l->package->name,
                    ],
                    'container' => $l->container?->only(['id', 'code']),
                    'measurement_unit' => $l->goods->measurement_unit->name,
                    'bb_date' => $l->bb_date,
                    'manufacture_date' => $l->manufacture_date,
                    'expiration_term' => $l->expiration_term,
                ];
            }),
        ];
    }
}
