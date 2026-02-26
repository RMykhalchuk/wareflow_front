<?php

namespace App\Tables\Container;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($container)
    {
        $containerArr = [];
        for ($i = 0; $i < count($container); $i++) {
            $containerArr[] = $container[$i]->toArray();
            $containerArr[$i]['type'] = $container[$i]->type->name;
        }

        return TableCollectionResource::make(array_values($containerArr))->setTotal($container->total());
    }

    #[\Override]
    /**
     * @return string
     */
    public function relationsSelectByField($relationName): string
    {
        $select = 'name';

        if ($relationName == 'type') {
            $select = 'container_types.name';
        }

        return $select;
    }
}
