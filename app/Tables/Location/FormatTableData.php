<?php

namespace App\Tables\Location;

use App\Http\Resources\Web\TableCollectionResource;
use App\Tables\Table\AbstractFormatTableData;


final class FormatTableData extends AbstractFormatTableData
{
    /**
     * @return TableCollectionResource
     */
    #[\Override]
    public function formatData($locations)
    {
        $locationArr = [];

        for ($i = 0; $i < count($locations); $i++) {
            $locationArr[] = $locations[$i]->toArray();

            if ($locations[$i]->company->company_type_id == 1) {
                $locationArr[$i]['company_name'] =
                    "{$locations[$i]->company->company->surname} {$locations[$i]->company->company->first_name}";
            } else {
                $locationArr[$i]['company_name'] = $locations[$i]->company->company->name;
            }
            $locationArr[$i]['country_name'] = $locations[$i]->country->name;
            $locationArr[$i]['settlement_name'] = $locations[$i]->settlement->name;
        }

        return TableCollectionResource::make(array_values($locationArr))->setTotal($locations->total());
    }


    #[\Override]
    /**
     * @return string
     */
    public function relationsSelectByField($relationName): string
    {
        $select = 'name';

        if ($relationName == 'company') {
            $select = "CASE
            WHEN companies.company_type_id = 1
            THEN (SELECT CONCAT(physical_companies.first_name, ' ', physical_companies.surname)
            FROM physical_companies
            WHERE physical_companies.id = companies.company_id)
            WHEN companies.company_type_id = 2
            THEN (SELECT legal_companies.name
            FROM legal_companies
            WHERE legal_companies.id = companies.company_id)
            END";
        } else if ($relationName == 'country_name') {
            $select = "_d_countries.name";
        } else if ($relationName == 'company_name') {
            $select = "_d_companies.name";
        }

        return $select;
    }
}
