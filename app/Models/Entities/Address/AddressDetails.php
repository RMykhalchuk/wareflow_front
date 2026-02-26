<?php

namespace App\Models\Entities\Address;

use App\Traits\AddressDetailsDataTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class AddressDetails extends Model
{
    use HasFactory;
    use AddressDetailsDataTrait;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Country>
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Settlement>
     */
    public function settlement(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Settlement::class, 'id', 'settlement_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Street>
     */
    public function street(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Street::class, 'id', 'street_id');
    }
}
