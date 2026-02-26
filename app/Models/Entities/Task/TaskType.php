<?php

namespace App\Models\Entities\Task;

use App\Traits\CompanySeparationDisable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TaskType extends Model
{
    use CompanySeparationDisable;
    use HasTranslations;

    protected $table = "_d_task_types";

    public $translatable = ['name'];
}
