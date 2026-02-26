<?php

namespace App\Models\Entities\Schedule;

use App\Traits\CompanySeparation;
use Illuminate\Database\Eloquent\Model;

final class SchedulePattern extends Model
{
    use CompanySeparation;

    protected $guarded = [];
}
