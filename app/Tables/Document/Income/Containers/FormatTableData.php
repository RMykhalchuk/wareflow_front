<?php

namespace App\Tables\Document\Income\Containers;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    private array $localIds = [];

    public function setLocalIds(array $localIds): self
    {
        $this->localIds = $localIds;

        return $this;
    }

    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($containers)
    {
        $formatedArray = [];

        foreach ($containers as $container) {
            $arr             = $container->toArray();
            $arr['local_id'] = $this->localIds[$container->id] ?? null;
            $formatedArray[] = $arr;
        }

        return TableCollectionResource::make(array_values($formatedArray))->setTotal($containers->total());
    }
}
