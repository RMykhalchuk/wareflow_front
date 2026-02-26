<?php

namespace App\Http\Resources\Api\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * InventoryItemRawResource.
 */
class InventoryItemRawResource extends JsonResource
{
    /**
     * @return array{
     *     id: string,
     *     qty: int|null,
     *     real_qty: int|null,
     *     created_at: string|null,
     *     updated_at: string|null,
     *     creator_id: string,
     *     inventory_id: string,
     *     cell_id: string,
     *     update_id: string|null,
     *     status: int,
     *     area: string|null
     * }
     */
    public function toArray($request): array
    {
        return [
            'id'           => (string) $this->id,
            'qty'          => $this->qty !== null ? (int) $this->qty : null,
            'real_qty'     => $this->real_qty !== null ? (int) $this->real_qty : null,
            'created_at'   => $this->created_at?->toISOString(),
            'updated_at'   => $this->updated_at?->toISOString(),
            'creator_id'   => (string) $this->creator_id,
            'inventory_id' => (string) $this->inventory_id,
            'cell_id'      => (string) $this->cell_id,
            'update_id'    => $this->update_id ? (string) $this->update_id : null,
            'status'       => (int) $this->status,
            'area'         => $this->area !== null ? (string) $this->area : null,
        ];
    }
}
