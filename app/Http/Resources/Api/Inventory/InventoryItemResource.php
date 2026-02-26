<?php

namespace App\Http\Resources\Api\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * InventoryItemResource.
 */
class InventoryItemResource extends JsonResource
{
    /**
     * @return array{
     *     id: string,
     *     local_id: string,
     *     zone: string|null,
     *     cell: string|null,
     *     status: array{
     *         value: string,
     *         label: string
     *     },
     *     leftovers: array{
     *         quantity: int,
     *         id: string|null
     *     },
     *     invented: array{
     *         name: string,
     *         date: string,
     *         time: string
     *     }
     * }
     */
    public function toArray($request): array
    {
        $row = $this->resource;

        return [
            'id'        => (string) ($row['id'] ?? ''),
            'local_id'  => (string) ($row['local_id'] ?? ''),
            'zone'      => $row['zone'] ?? null,
            'cell'      => $row['cell'] ?? null,
            'status'    => [
                'value' => $row['status']['value'] ?? '',
                'label' => $row['status']['label'] ?? '',
            ],
            'leftovers' => [
                'quantity' => (int) ($row['leftovers']['quantity'] ?? 0),
                'id'       => $row['leftovers']['id'] ?? null,
            ],
            'invented'  => [
                'name' => $row['invented']['name'] ?? '',
                'date' => $row['invented']['date'] ?? '',
                'time' => $row['invented']['time'] ?? '',
            ],
        ];
    }
}
