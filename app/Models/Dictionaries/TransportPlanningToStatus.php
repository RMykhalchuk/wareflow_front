<?php

namespace App\Models\Dictionaries;

use App\Models\Entities\Address\AddressDetails;
use App\Models\Entities\TransportPlanning\TransportPlanning;
use App\Models\Entities\TransportPlanning\TransportPlanningFailure;
use Illuminate\Database\Eloquent\Model;

final class TransportPlanningToStatus extends Model
{

    protected $guarded = [];

    protected $table = 'transport_planning_to_statuses';

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<AddressDetails>
     */
    public function address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AddressDetails::class, 'address_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransportPlanning>
     */
    public function transport_planning(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportPlanning::class, 'transport_planning_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransportPlanningStatus>
     */
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportPlanningStatus::class, 'status_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<TransportPlanningFailure>
     */
    public function failure(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TransportPlanningFailure::class, 'status_id');
    }
}
