<?php

namespace App\Tables\DocumentType;

use App\Http\Resources\Web\TableCollectionResource;
use App\Services\Web\Document\TableFields;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($documents)
    {
        $formatedArray = [];
        for ($i = 0; $i < count($documents); $i++) {
            $formatedArray[] = $documents[$i]->toArray();

            $formatedArray[$i] = array_merge($formatedArray[$i], TableFields::format($formatedArray[$i], 'header'));

            $customBlocks = json_decode($formatedArray[$i]['data'], true)['custom_blocks'];
            for ($j = 0; $j < count($customBlocks); $j++) {
                foreach ($customBlocks[$j] as $key => $value) {
                    $formatedArray[$i]['data->custom_blocks->' . $j . '->' . $key]
                        = TableFields::getFormattedField($key, $value);
                };
            }
        }

        return TableCollectionResource::make(array_values($formatedArray))->setTotal($documents->total());
    }
}
