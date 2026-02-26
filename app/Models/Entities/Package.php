<?php

namespace App\Models\Entities;

use App\Models\Dictionaries\PackageType;
use App\Models\Entities\Document\IncomeDocumentLeftover;
use App\Models\Entities\Leftover\Leftover;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

final class Package extends Model
{
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    protected $appends = ['barcodeString', 'canEdit'];


    public function type(): HasOne
    {
        return $this->hasOne(PackageType::class, 'id', 'type_id');
    }

    public function barcode(): MorphOne
    {
        return $this->morphOne(Barcode::class, 'entity');
    }

    public function getBarcodeStringAttribute(): ?string
    {
        if ($this->relationLoaded('barcode')) {
            return $this->barcode?->barcode;
        }

        return null;
    }

    public function child()
    {
        return $this->hasOne(Package::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Package::class, 'parent_id');
    }

    public function leftovers()
    {
        return $this->hasMany(Leftover::class);
    }

    public function income_document_leftovers()
    {
        return $this->hasMany(IncomeDocumentLeftover::class);
    }

    public function getCanEditAttribute(): bool
    {
        return $this->leftovers_count === 0 && $this->income_document_leftovers_count === 0;
    }
}
