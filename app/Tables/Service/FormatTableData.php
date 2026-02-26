<?php

namespace App\Tables\Service;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($services)
    {
        $servicesArr = [];
        for ($i = 0; $i < count($services); $i++) {
            $servicesArr[] = $services[$i]->toArray();
            $servicesArr[$i]['category'] = $services[$i]?->category?->name;
        }

        return TableCollectionResource::make(array_values($servicesArr))->setTotal($services->total());
    }

    #[\Override]
    /**
     * @return string
     *
     * @psalm-return 'name'|'service_categories.name'
     */
    public function relationsSelectByField($relationName): string
    {
        $select = 'name';

        if ($relationName == 'category') {
            $select = 'service_categories.name';
        }

        return $select;
    }
}
