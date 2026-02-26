<?php

namespace App\Models\Entities\Leftover;


use App\Models\Entities\Container\ContainerRegister;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeftoverToContainerRegister extends Model
{
    use SoftDeletes;
    use HasUuid;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    public function containerRegister(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ContainerRegister::class, 'container_register_id', 'id');
    }

    public function leftover(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Leftover::class, 'leftover_id', 'id');
    }

}
