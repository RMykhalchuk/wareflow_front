<?php

namespace App\Tables\AdditionalEquipment;

use App\Helpers\PostgreHelper;
use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;
use Illuminate\Support\Facades\DB;

final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($additionalEquipments)
    {
        $additionalEquipmentsArr = [];
        for ($i = 0; $i < count($additionalEquipments); $i++) {
            $additionalEquipmentsArr[] = $additionalEquipments[$i]->toArray();

            if ($additionalEquipments[$i]->company->company_type_id == 1) {
                $additionalEquipmentsArr[$i]['company'] =
                    "{$additionalEquipments[$i]->company->company->first_name}
                     {$additionalEquipments[$i]->company->company->surname}";
            } else {
                $additionalEquipmentsArr[$i]['company'] = $additionalEquipments[$i]->company->company->name;
            }
            $additionalEquipmentsArr[$i]['dnz'] = $additionalEquipments[$i]->license_plate;
            $additionalEquipmentsArr[$i]['model'] =
                "{$additionalEquipments[$i]->brand->name}
                {$additionalEquipments[$i]->model->name}";
            $additionalEquipmentsArr[$i]['car'] =
                "{$additionalEquipments[$i]->transport->brand->name}
                {$additionalEquipments[$i]->transport->model->name}";
            $additionalEquipmentsArr[$i]['typeLoad'] =
                implode('|', $additionalEquipments[$i]->downloadMethods()->values()->toArray());
        }

        return TableCollectionResource::make(array_values($additionalEquipmentsArr))
            ->setTotal($additionalEquipments->total());
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
        if ($fieldName == 'model') {
            $fieldName =
                DB::raw(PostgreHelper::dbConcat('_d_additional_equipment_brands.name', '_d_additional_equipment_models.name'));
        } elseif ($fieldName == 'dnz') {
            $fieldName =
                DB::raw('license_plate');
        } elseif ($fieldName == 'car') {
            $fieldName =
                DB::raw(PostgreHelper::dbConcat('transport_info.brand_name', 'transport_info.model_name'));
        } elseif ($fieldName == 'typeLoad') {
            $fieldName =
                DB::raw('methods.new_download_methods');
        }

        return $fieldName;
    }

    #[\Override]
    /**
     * @return string
     *
     * @psalm-return string
     */
    public function relationsSelectByField($relationName): string
    {
        $select = 'name';

        $concat = PostgreHelper::dbConcat('physical_companies.first_name', 'physical_companies.surname');

        if ($relationName == 'company') {
            $select = "
            CASE
            WHEN companies.company_type_id = 1
            THEN {$concat}
            FROM physical_companies
            WHERE physical_companies.id = companies.company_id)
            WHEN companies.company_type_id = 2
            THEN (SELECT legal_companies.name FROM legal_companies
            WHERE legal_companies.id = companies.company_id) END";
        }

        return $select;
    }
}
