<?php

namespace App\Tables\Inventory\Items\Leftovers;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryLeftoverResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return (array) $this->resource;
    }
}

