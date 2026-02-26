<?php

namespace App\Tables\SkuInDocument;

use App\Http\Resources\Web\TableCollectionResource;
use App\Services\Web\Document\TableFields;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($sku)
    {
        $formatedArray = [];
        for ($i = 0; $i < count($sku); $i++) {
            $formatedArray[] = $sku[$i]->toArray();
            $formatedArray[$i]['name'] = $sku[$i]->goods->name;
            foreach (json_decode($formatedArray[$i]['data'], true) as $key => $value) {
                $formatedArray[$i]['data->' . $key] = TableFields::getFormattedField($key, $value);
            };
        }

        return TableCollectionResource::make(array_values($formatedArray))->setTotal($sku->total());
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
            $fieldName = DB::raw('goods.name');
        } elseif ($fieldName == 'id') {
            $fieldName = DB::raw('sku_by_documents.id');
        }

        return $fieldName;
    }
}
