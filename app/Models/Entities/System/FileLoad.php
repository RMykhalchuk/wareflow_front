<?php

namespace App\Models\Entities\System;

use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class FileLoad extends Model
{
    use HasFactory;
    use HasUuid;

    public $guarded = [];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
