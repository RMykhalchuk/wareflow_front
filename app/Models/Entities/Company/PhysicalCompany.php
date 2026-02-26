<?php

namespace App\Models\Entities\Company;


use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class PhysicalCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;


    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\MorphOne<Company>
     */
    public function related(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Company::class, 'company');
    }
}
