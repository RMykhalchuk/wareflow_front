<?php

namespace App\Http\Resources\Api\Inventory;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * InventoryZoneItemsResource.
 */
class InventoryZoneItemsResource extends JsonResource
{
    /**
     * @return array{
     *     inventory: array{
     *         id: string|null,
     *         local_id: int|null
     *     },
     *     total: int,
     *     zones: list<array{
     *         zone: string,
     *         total: int,
     *         status_2: int,
     *         counts_by_status: array<string|int, int>,
     *         cells: list<array{
     *             inventory_item_id: string,
     *             cell: string|null,
     *             status: array{
     *                 value: int|null,
     *                 label: string
     *             }
     *         }>
     *     }>
     * }
     */
    public function toArray($request): array
    {
        $inventory = $this['inventory'] ?? null;

        return [
            'inventory' => [
                'id'       => $inventory->id       ?? null,
                'local_id' => $inventory->local_id ?? null,
            ],
            'total' => (int) ($this['total'] ?? 0),
            'zones' => collect($this['zones'] ?? [])
                ->map(function (array $zone): array {
                    return [
                        'zone'             => (string) ($zone['zone'] ?? ''),
                        'total'            => (int) ($zone['total'] ?? 0),
                        'status_2'         => (int) ($zone['status_2'] ?? 0),
                        'counts_by_status' => $zone['counts_by_status'] ?? [],
                        'cells'            => collect($zone['cells'] ?? [])
                            ->map(function (array $cell): array {
                                return [
                                    'inventory_item_id' => (string) ($cell['inventory_item_id'] ?? ''),
                                    'cell'              => $cell['cell'] ?? null,
                                    'status'            => [
                                        'value' => $cell['status']['value'] ?? null,
                                        'label' => (string) ($cell['status']['label'] ?? ''),
                                    ],
                                ];
                            })
                            ->all(),
                    ];
                })
                ->all(),
        ];
    }
}
