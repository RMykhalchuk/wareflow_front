<?php

namespace App\Tables\GoodsPackage;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($packages)
    {
        $packagesArr = [];
        for ($i = 0; $i < count($packages); $i++) {
            $packagesArr[] = $packages[$i]->toArray();
            $packagesArr[$i]['type'] = $packages[$i]->type->name;
            $packagesArr[$i]['count'] = $packages[$i]->number;
            $packagesArr[$i]['packingWeight'] = $packages[$i]->weight;
            $packagesArr[$i]['weightNet'] = $packages[$i]->weight_netto;
            $packagesArr[$i]['weightGross'] = $packages[$i]->weight_brutto;
            $packagesArr[$i]['packingSetMain'] = $packages[$i]->is_default;
            $packagesArr[$i]['size'] = [
                'height' => $packages[$i]->height,
                'width' => $packages[$i]->width,
                'length' => $packages[$i]->depth
            ];
        }

        return TableCollectionResource::make(array_values($packagesArr))->setTotal($packages->total());
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
        if ($fieldName == 'count') {
            $fieldName = DB::raw('packages.number');
        } elseif ($fieldName == 'packingWeight') {
            $fieldName = DB::raw('packages.weight');
        } elseif ($fieldName == 'weightNet') {
            $fieldName = DB::raw('packages.weight_netto');
        } elseif ($fieldName == 'weightGross') {
            $fieldName = DB::raw('packages.weight_brutto');
        } elseif ($fieldName == 'size') {
            $fieldName = DB::raw("CONCAT(packages.height, ' ', packages.width, ' ', packages.depth)");
        }

        return $fieldName;
    }

    #[\Override]
    /**
     * @return string
     *
     * @psalm-return 'name'|'package_types.name'
     */
    public function relationsSelectByField($relationName): string
    {
        $select = 'name';

        if ($relationName == 'type') {
            $select = 'package_types.name';
        }

        return $select;
    }
}
