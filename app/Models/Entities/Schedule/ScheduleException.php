<?php

namespace App\Models\Entities\Schedule;

use App\Models\Dictionaries\ExceptionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class ScheduleException extends Model
{

    protected $guarded = [];
    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<ExceptionType>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ExceptionType::class, 'id', 'type_id');
    }
}
