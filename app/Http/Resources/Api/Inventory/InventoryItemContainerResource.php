<?php

namespace App\Http\Resources\Api\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * InventoryItemContainerResource.
 */
class InventoryItemContainerResource extends JsonResource
{
    /**
     * @return array{
     *     id: string,
     *     uuid: string,
     *     created_at: string|null,
     *     updated_at: string|null,
     *     leftovers_count: int,
     *     leftovers: array<int, array{
     *         id: string,
     *         leftover_id: string|null,
     *         real: int,
     *         name: array{
     *             title: string|null,
     *             barcode: string|null,
     *             manufacturer: string|null,
     *             category: string|null,
     *             brand: string|null
     *         },
     *         placing: array{
     *             cell_id: string|null,
     *             pallet: string,
     *             warehouse: string|null,
     *             zone: string|null,
     *             sector: string|null,
     *             row: string|null,
     *             cell: string|null,
     *             code: string
     *         },
     *         manufactured: string|null,
     *         expiry: string|null,
     *         party: string|null,
     *         condition: int|null,
     *         package: string|null,
     *         current_leftovers: int|null,
     *         leftovers_erp: int,
     *         divergence: string,
     *         responsible_name: string,
     *         responsible_date: string,
     *         responsible_time: string,
     *         measurement_unit: array{
     *             id: string,
     *             name: string|null
     *         }|null,
     *         package_current: array{
     *             id: string,
     *             name: string|null,
     *             qty: int|null
     *         }|null,
     *         packages_all: array<int, array{
     *             id: string,
     *             name: string,
     *             qty: int
     *         }>
     *     }>
     * }
     */
    public function toArray($request): array
    {
        $row = $this->resource;

        return [
            'id'              => (string) ($row['id'] ?? ''),
            'uuid'            => (string) ($row['uuid'] ?? ''),
            'created_at'      => $row['created_at'] ?? null,
            'updated_at'      => $row['updated_at'] ?? null,
            'leftovers_count' => (int) ($row['leftovers_count'] ?? 0),
            'leftovers'       => $row['leftovers'] ?? [],
        ];
    }
}
