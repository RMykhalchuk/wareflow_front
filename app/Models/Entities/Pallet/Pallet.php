<?php

namespace App\Models\Entities\Pallet;

use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Pallet extends Model
{
    use HasFactory;
    use HasUuid;
    use HasLocalId;

    protected $guarded = [];
}
