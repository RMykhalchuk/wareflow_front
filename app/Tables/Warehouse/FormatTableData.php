<?php

namespace App\Tables\Warehouse;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($warehouse)
    {
        $warehouseArr = [];

        for ($i = 0; $i < count($warehouse); $i++) {
            $warehouseArr[] = $warehouse[$i]->toArray();

            $warehouseArr[$i]['type'] = $warehouse[$i]->type?->name;

            $warehouseArr[$i]['location'] = $warehouse[$i]->location?->toArray();

            if (method_exists($warehouse[$i], 'erpWarehouses')) {
                $erpCollection = $warehouse[$i]->erpWarehouses;
                $warehouseArr[$i]['erp'] = $erpCollection && $erpCollection->count()
                    ? implode(', ', $erpCollection->pluck('name')->toArray())
                    : null;
            } else {
                $warehouseArr[$i]['erp'] = $warehouse[$i]->warehouseErp?->name;
            }
        }

        return TableCollectionResource::make(array_values($warehouseArr))->setTotal($warehouse->total());
    }

    /**
     * @return (array|string)[]|\Illuminate\Contracts\Database\Query\Expression|string
     *
     * @psalm-return \Illuminate\Contracts\Database\Query\Expression|array<int|string,
     *      array<int|string, mixed>|string>|string
     */
    #[\Override]
    public function renameFields($fieldName)
    {
        if ($fieldName == 'name') {
            $fieldName = 'warehouses.name';
        } elseif ($fieldName == 'location') {
            $fieldName = 'locations.name';
        } elseif ($fieldName == 'warehouse_erp') {
            $fieldName = 'warehouses_erp.name';
        }

        return $fieldName;
    }

    #[\Override]
    public function relationsByField(string|array $fieldName)
    {
        if ($fieldName === 'warehouse_erp' || $fieldName === 'erp') {
            return 'erpWarehouses';
        }

        return parent::relationsByField($fieldName);
    }
}
