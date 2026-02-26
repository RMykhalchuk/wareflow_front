<?php

namespace App\Tables\GoodsBarcode;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($barcodes)
    {
        $barcodesArr = [];
        for ($i = 0; $i < count($barcodes); $i++) {
            $barcodesArr[] = $barcodes[$i]->toArray();
        }

        return TableCollectionResource::make(array_values($barcodesArr))->setTotal($barcodes->total());
    }
}
