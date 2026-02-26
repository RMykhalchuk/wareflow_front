<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class TransportPlanningFailureType extends Model
{
    use HasTranslations;

    protected $guarded = [];

    protected $table = '_d_transport_planning_failure_types';

    public $translatable = ['name'];
}
