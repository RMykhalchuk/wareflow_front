<?php

namespace App\Models\Entities\Company;

use App\Models\User;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

final class CompanyRequest extends Model
{
    use HasUuid;
    use HasLocalId;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    public const IN_PROGRESS = 0;
    public const APPROVED = 1;
    public const DECLINED = 2;

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
}
