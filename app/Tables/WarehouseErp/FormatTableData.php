<?php

namespace App\Tables\WarehouseErp;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($warehouseErp): TableCollectionResource
    {
        $warehouseArr = [];
        for ($i = 0; $i < count($warehouseErp); $i++) {
            $warehouseArr[] = $warehouseErp[$i]->toArray();
        }
        return TableCollectionResource::make(array_values($warehouseArr))->setTotal($warehouseErp->total());
    }
}
