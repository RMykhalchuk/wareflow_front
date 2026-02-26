<?php

namespace App\Http\Resources\Api\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * InventoryResource.
 */
class InventoryResource extends JsonResource
{
    /**
     * @return array{
     *     id: string,
     *     local_id: int,
     *     show_leftovers: bool,
     *     restrict_goods_movement: bool,
     *     process_cell: int,
     *     status: int,
     *     type: string,
     *     creator_id: string|null,
     *     warehouse_id: string,
     *     warehouse_erp_id: string|null,
     *     zone_id: string|null,
     *     sector_id: string|null,
     *     row_id: string|null,
     *     manufacturer_id: string|null,
     *     supplier_id: string|null,
     *     nomenclature_id: string|null,
     *     start_date: string|null,
     *     end_date: string|null,
     *     comment: string|null,
     *     created_at: string|null,
     *     updated_at: string|null,
     *     deleted_at: string|null,
     *     performer_id: string|null,
     *     cell_id: string|null,
     *     priority: int,
     *     category_subcategory: string|null,
     *     brand: string|null,
     *     erp_id: string|null,
     *     items_data: mixed
     * }
     */
    public function toArray($request): array
    {
        return [
            'id'                     => $this->id,
            'local_id'               => $this->local_id,
            'show_leftovers'         => (bool) $this->show_leftovers,
            'restrict_goods_movement'=> (bool) $this->restrict_goods_movement,
            'process_cell'           => (int) $this->process_cell,
            'status'                 => (int) $this->status,
            'type'                   => $this->type,
            'creator_id'             => $this->creator_id,
            'warehouse_id'           => $this->warehouse_id,
            'warehouse_erp_id'       => $this->warehouse_erp_id,
            'zone_id'                => $this->zone_id,
            'sector_id'              => $this->sector_id,
            'row_id'                 => $this->row_id,
            'manufacturer_id'        => $this->manufacturer_id,
            'supplier_id'            => $this->supplier_id,
            'nomenclature_id'        => $this->nomenclature_id,
            'start_date'             => $this->start_date,
            'end_date'               => $this->end_date,
            'comment'                => $this->comment,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
            'deleted_at'             => $this->deleted_at,
            'performer_id'           => $this->performer_id,
            'cell_id'                => $this->cell_id,
            'priority'               => (int) $this->priority,
            'category_subcategory'   => $this->category_subcategory,
            'brand'                  => $this->brand,
            'erp_id'                 => $this->erp_id,
            'items_data'             => $this->items_data,
        ];
    }
}
