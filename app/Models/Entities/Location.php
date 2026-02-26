<?php

namespace App\Models\Entities;

use App\Models\Entities\Address\Country;
use App\Models\Entities\Address\Settlement;
use App\Models\Entities\Company\Company;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'local_id',
        'name',
        'company_id',
        'country_id',
        'settlement_id',
        'street_info',
        'coordinates',
        'creator_company_id',
        'url'
    ];

    protected $casts = [
        'street_info' => 'array',
    ];

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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

}
