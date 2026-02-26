<?php

namespace App\Models\Entities\Contract;


use App\Models\Entities\Company\Company;
use App\Traits\CompanySeparation;
use App\Traits\FilterByCompany;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Contract extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use FilterByCompany;
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function counterparty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'counterparty_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Regulation>
     */
    public function company_regulation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Regulation::class, 'company_regulation_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Regulation>
     */
    public function counterparty_regulation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Regulation::class, 'counterparty_regulation_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<ContractComment>
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContractComment::class, 'contract_id');
    }
}
