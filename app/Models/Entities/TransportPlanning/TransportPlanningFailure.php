<?php

namespace App\Models\Entities\TransportPlanning;

use App\Models\Dictionaries\TransportPlanningFailureType;
use App\Models\Dictionaries\TransportPlanningToStatus;
use Illuminate\Database\Eloquent\Model;

final class TransportPlanningFailure extends Model
{
    protected $guarded = [];

    protected $table = 'transport_planning_failures';

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransportPlanningFailureType>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportPlanningFailureType::class, 'type_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransportPlanningToStatus>
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportPlanningToStatus::class, 'status_id');
    }
}
