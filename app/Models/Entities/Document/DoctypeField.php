<?php

namespace App\Models\Entities\Document;

use App\Traits\CompanySeparationDisable;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class DoctypeField extends Model
{
    use HasFactory;
    use HasUuid;
    use HasLocalId;
    use CompanySeparationDisable;

    public $timestamps = false;
    protected $guarded = [];
}
