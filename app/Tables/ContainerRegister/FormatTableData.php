<?php

namespace App\Tables\ContainerRegister;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($containerRegister) : TableCollectionResource
    {
        $formatedArray = [];
        for ($i = 0; $i < count($containerRegister); $i++) {
            $formatedArray[] = $containerRegister[$i]->toArray();

            $formatedArray[$i]['container'] = $containerRegister[$i]->container->name;
            $formatedArray[$i]['location'] = $containerRegister[$i]?->cell?->allocation;
            $formatedArray[$i]['type'] = $containerRegister[$i]->container->type->name;
            $formatedArray[$i]['status'] = $containerRegister[$i]->status;

        }

        return TableCollectionResource::make(array_values($formatedArray))->setTotal($containerRegister->total());
    }
}
