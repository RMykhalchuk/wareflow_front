<?php

namespace App\Models\Entities\Logs;

use App\Models\User;
use App\Traits\CompanySeparation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EntityLog extends Model
{
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    public function entity(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
