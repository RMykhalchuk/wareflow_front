<?php

namespace App\Models\Entities;

use App\Traits\CompanySeparation;
use App\Traits\FilterByCompany;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Barcode extends Model
{
    use HasUuid;
    use HasLocalId;
    use FilterByCompany;
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    protected $guarded = [];
}
