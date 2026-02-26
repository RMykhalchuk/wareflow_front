<?php

namespace App\Tables\Goods;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($goods)
    {
        $goodsArr = [];
        for ($i = 0; $i < count($goods); $i++) {
            $goodsArr[] = $goods[$i]->toArray();
            $goodsArr[$i]['manufacturer'] = $goods[$i]?->manufacturerCompany?->full_name ?: $goods[$i]?->manufacturer;
            $goodsArr[$i]['country'] = $goods[$i]->manufacturer_country?->name;
            $goodsArr[$i]['category'] = $goods[$i]?->category?->name;
            $goodsArr[$i]['barcode'] = $goods[$i]->barcodes->pluck('barcode')->flatten()->toArray();
            $goodsArr[$i]['status'] = $goods[$i]->status;
            $goodsArr[$i]['brand'] = $goods[$i]?->brandCompany?->full_name ?: $goods[$i]->brand;
        }


        return TableCollectionResource::make(array_values($goodsArr))->setTotal($goods->total());
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

        if ($fieldName == 'id') {
            $fieldName = DB::raw('goods.id');
        }

        return $fieldName;
    }

    /**
     * @return (array|string)[]|string
     *
     * @psalm-return array<int|string, array<int|string,
     *      mixed>|string>|string
     */
    #[\Override]
    public function relationsByField($fieldName)
    {
        if ($fieldName == 'country') {
            $fieldName = 'manufacturer_country';
        }

        return $fieldName;
    }

    #[\Override]
    /**
     * @return string
     *
     * @psalm-return 'manufacturer_country.name'|'name'
     */
    public function relationsSelectByField($relationName): string
    {
        $select = 'name';

        if ($relationName == 'country') {
            $select = 'manufacturer_country.name';
        }

        return $select;
    }

    #[\Override]
    public function joinsByField($fieldName, $model)
    {
        if ($fieldName == 'company') {
            $model->leftJoin(
                DB::raw("
            (SELECT companies.id as first_company_id,
            (CASE
            WHEN companies.company_type_id = 1
            THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname)
            ELSE legal_companies.name END) as company_name
            FROM companies
            LEFT JOIN physical_companies ON companies.company_id = physical_companies.id
            LEFT JOIN legal_companies ON companies.company_id = legal_companies.id) as first_companies"),
                'goods.company_id',
                '=',
                'first_companies.first_company_id'
            );
        } elseif ($fieldName == 'manufacturer') {
            $model->leftJoin(
                DB::raw("(
            SELECT companies.id as manufacturer_id,
            (CASE WHEN companies.company_type_id = 1
            THEN CONCAT(physical_companies.first_name, ' ', physical_companies.surname)
            ELSE legal_companies.name END) as manufacturer_name
            FROM companies
            LEFT JOIN physical_companies ON companies.company_id = physical_companies.id
            LEFT JOIN legal_companies ON companies.company_id = legal_companies.id) as second_companies"),
                'goods.manufacturer_id',
                '=',
                'second_companies.manufacturer_id'
            );
        }

        return $model;
    }
}
