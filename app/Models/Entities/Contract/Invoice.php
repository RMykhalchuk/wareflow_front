<?php

namespace App\Models\Entities\Contract;

use App\Models\Entities\Company\Company;
use App\Models\Entities\Document\Document;
use App\Traits\CompanySeparation;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

final class Invoice extends Model
{
    protected $guarded = [];

    use HasUuid;
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function company_provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_provider_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function company_customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_customer_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function responsible_supply(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'responsible_supply_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function responsible_receive(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'responsible_receive_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Document>
     */
    public function documents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'invoice_documents',
            'invoice_id',
            'document_id'
        );
    }
}
