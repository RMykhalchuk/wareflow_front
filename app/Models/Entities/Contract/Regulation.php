<?php

namespace App\Models\Entities\Contract;

use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\RegulationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

final class Regulation extends Model
{
    use SoftDeletes;
    use NodeTrait;
    use RegulationTrait;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    public const COMMERCIAL_TYPE = 0;
    public const WAREHOUSE_TYPE = 1;
    public const TRANSPORT_TYPE = 2;

    public const CUSTOMER_SIDE = 0;

    public const PERFORMER_SIDE = 1;

    /*public function contracts() :HasMany
    {
        return $this->hasMany(Contract::class, 'category_id');
    }

    public function companyContracts() {
        return $this->hasMany(Contract::class, 'company_regulation_id');
    }

    public function counterpartyContracts() {
        return $this->hasMany(Contract::class, 'counterparty_regulation_id');
    }

    public function allContracts() {
        return $this->companyContracts->merge($this->counterpartyContracts);
    }*/

    public static function store(\Illuminate\Http\Request $request)
    {
        $data = $request->except(['_token']);

        $regulation = Regulation::create($data);

        Regulation::fixTree();

        return $regulation->id;
    }
}
