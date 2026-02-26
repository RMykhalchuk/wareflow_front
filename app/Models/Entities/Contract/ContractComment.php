<?php

namespace App\Models\Entities\Contract;

use App\Models\Entities\Company\Company;
use Illuminate\Database\Eloquent\Model;

final class ContractComment extends Model
{
    protected $guarded = [];

    public $table = 'contracts_comments';

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
