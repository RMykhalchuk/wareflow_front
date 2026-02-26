<?php

namespace App\Tables\LeftoverByPartyAndPacking;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($leftovers)
    {
        $leftoversArr = [];
        for ($i = 0; $i < count($leftovers); $i++) {
            $leftoversArr[] = $leftovers[$i]->toArray();
            $leftoversArr[$i]['sku'] = $leftovers[$i]->goods->name;
            $leftoversArr[$i]['packaging'] = $leftovers[$i]->packages->first()->name;
        }

        return TableCollectionResource::make(array_values($leftoversArr))->setTotal($leftovers->total());
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
        if ($fieldName == 'sku') {
            $fieldName = DB::raw('goods.name');
        } elseif ($fieldName == 'party') {
            $fieldName = DB::raw('goods.party');
        } elseif ($fieldName == 'packaging') {
            $fieldName = DB::raw('packages.name');
        }

        return $fieldName;
    }
}
