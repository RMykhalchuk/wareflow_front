<?php

namespace App\Tables\ContainerInDocument;

use App\Http\Resources\Web\TableCollectionResource;
use App\Services\Web\Document\TableFields;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($container)
    {
        $formatedArray = [];
        for ($i = 0; $i < count($container); $i++) {
            $formatedArray[] = $container[$i]->toArray();

            foreach (json_decode($formatedArray[$i]['data'], true) as $key => $value) {
                $formatedArray[$i]['data->' . $key] = TableFields::getFormattedField($key, $value);
            };
        }

        return TableCollectionResource::make(array_values($formatedArray))->setTotal($container->total());
    }
}
