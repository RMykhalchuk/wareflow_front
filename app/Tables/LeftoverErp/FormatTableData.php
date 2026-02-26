<?php

namespace App\Tables\LeftoverErp;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    #[\Override]
    public function formatData($leftovers)
    {
        $items = [];
        foreach ($leftovers as $leftover) {
            $items[] = [
                'id'       => $leftover->id,
                'goods'    => [
                    'id'          => $leftover->goods?->id,
                    'name'        => $leftover->goods?->name,
                    'barcode'     => $leftover->goods?->barcodes?->pluck('barcode')?->flatten()?->toArray(),
                    'manufacturer'=> $leftover->goods?->manufacturer,
                    'category'    => $leftover->goods?->category?->name,
                    'provider'    => $leftover->goods?->provider,
                    'erp_id'    => $leftover->goods?->erp_id,
                    'measurement_unit' => $leftover->goods?->measurement_unit,
                ],
                'warehouse_erp' => [
                    'id'   => $leftover->warehouseErp?->id,
                    'name' => $leftover->warehouseErp?->name,
                    'id_erp' => $leftover->warehouseErp?->id_erp,
                ],
                'batch'    => $leftover->batch,
                'local_id'    => $leftover->local_id,
                'quantity_erp' => $leftover->quantity,
                'updated_at' => $leftover->updated_at,
            ];
        }

        return TableCollectionResource::make($items)->setTotal($leftovers->total());
    }

    #[\Override]
    public function renameFields($fieldName)
    {
        if ($fieldName === 'sku') {
            return DB::raw('goods.name');
        }
        return $fieldName;
    }
}
