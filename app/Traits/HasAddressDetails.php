<?php

namespace App\Traits;

use App\Models\Entities\Address\AddressDetails;
use Illuminate\Support\Facades\DB;

trait HasAddressDetails
{
    public function scopeJoinAddress($q, $columnForJoin = 'address_id', $withCountry = true)
    {
        $addressDetails = AddressDetails::addFullAddress('full_address', true, $withCountry)->toSql();

        return $q->leftJoin(DB::raw("($addressDetails) AS address_details"), $columnForJoin, '=', 'address_details.id');
    }
}
