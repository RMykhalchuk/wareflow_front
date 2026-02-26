<?php

namespace App\Http\Resources\Terminal\ManualMovement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContainerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'count' => $this->whenCounted('leftovers', $this->leftovers_count ?? null),
            'cell' => $this->whenLoaded('cell', fn() => [
                'id' => $this->cell?->id,
                'code' => $this->cell?->code,
            ]),
        ];
    }
}
