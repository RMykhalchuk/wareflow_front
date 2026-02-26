<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait AddressDetailsDataTrait
{
    public function scopeAddFullAddress($q, $alias = 'full_address', $useDefaultSelect = true, $withCountry = true)
    {
        if ($withCountry) {
            $q->leftJoin('countries', 'address_details.country_id', '=', 'countries.id');
        }

        $q->leftJoin('settlements', 'address_details.settlement_id', '=', 'settlements.id')
            ->leftJoin('streets', 'address_details.street_id', '=', 'streets.id');

        if ($useDefaultSelect) {
            $q->select('address_details.id as id');
        }

        $concat = '';

        if ($withCountry) {
            $concat .= 'countries.name,';
        }

        $concat .= 'settlements.name, streets.name, address_details.building_number, address_details.apartment_number';

        return $q->addSelect(DB::raw("CONCAT_WS(', ', $concat) as $alias"));
    }
}
