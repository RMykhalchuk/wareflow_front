<?php

namespace App\Models\Entities\Company;

use App\Models\Dictionaries\LegalType;
use App\Models\Entities\Address\AddressDetails;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * LegalCompany.
 */
final class LegalCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuid;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $appends = [
        'reg_doc_url',
        'ust_doc_url',
    ];

    /**
     * @psalm-return MorphOne<Company>
     */
    public function related(): MorphOne
    {
        return $this->morphOne(Company::class, 'company');
    }

    /**
     * @psalm-return HasOne<LegalType>
     */
    public function legal_type(): HasOne
    {
        return $this->hasOne(LegalType::class, 'id', 'legal_type_id');
    }

    /**
     * @psalm-return HasOne<AddressDetails>
     */
    public function address(): HasOne
    {
        return $this->hasOne(AddressDetails::class, 'id', 'legal_address_id');
    }

    /**
     * @return string|null
     */
    public function getRegDocUrlAttribute(): ?string
    {
        if (empty($this->reg_doctype)) {
            return null;
        }

        return asset('uploads/company/docs/registration/' . $this->id . '.' . $this->reg_doctype);
    }

    /**
     * @return string|null
     */
    public function getUstDocUrlAttribute(): ?string
    {
        if (empty($this->install_doctype)) {
            return null;
        }

        return asset('uploads/company/docs/install/' . $this->id . '.' . $this->install_doctype);
    }
}
