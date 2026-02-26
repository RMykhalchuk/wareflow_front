<?php

namespace App\Http\Resources\Terminal\Income;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductViewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'product' => [
                'id' => $this->resource['product']['id'],
                'name' => $this->resource['product']['name'] ?? null,
                'measurement_unit' => [
                    'name' => $this->resource['product']['measurement_unit']['name'] ?? null,
                    'key'  => $this->resource['product']['measurement_unit']['key'] ?? null,
                ],
                'barcode' => $this->resource['product']->barcodes->first()?->barcode,
            ],
            'dictionary' => [
                'packages' => $this->resource['dictionary']['packages'],
                'containers' => $this->resource['dictionary']['containers'],
                'expiration_date' => $this->resource['product']['expiration_date']
            ],
        ];
    }
}
