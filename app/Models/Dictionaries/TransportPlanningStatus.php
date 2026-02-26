<?php

namespace App\Models\Dictionaries;

use App\Traits\HasAddressDetails;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class TransportPlanningStatus extends Model
{
    use HasAddressDetails, HasTranslations;

    protected $guarded = [];

    protected $table = '_d_transport_planning_statuses';

    public $translatable = ['name'];
}
