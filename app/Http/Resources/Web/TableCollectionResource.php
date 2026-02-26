<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class TableCollectionResource extends ResourceCollection
{
    protected $totalRecords;

    public function setTotal(int $totalRecords): static
    {
        $this->totalRecords = $totalRecords;
        return $this;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    #[\Override]
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'total' => $this->totalRecords,
        ];
    }
}
