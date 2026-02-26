<?php

namespace App\Models\Entities\Task;

use App\Enums\Task\TaskFormationType;
use App\Enums\Task\TaskStatus;
use App\Models\Entities\Document\Document;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\User;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;
    use LogActivity;

    protected $keyType      = 'string';
    public    $incrementing = false;


    protected $guarded = [];

    protected $casts = [
        'executors' => 'array',
        'status' => TaskStatus::class,
        'formation_type' => TaskFormationType::class
    ];


    protected $appends = ['status_info'];

    public function scopeInCurrentWarehouse($query): void
    {
        $query->whereHas('cell', function ($q) {
            $q->inCurrentWarehouse();
        });
    }

    public function getExecutorUsersAttribute()
    {
        return $this->executors ? User::whereIn('id', $this->executors)->get() : null;
    }


    public function items()
    {
        return $this->hasMany(TaskItem::class);
    }

    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TaskType::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function getStatusInfoAttribute()
    {
        return $this->status->toArray();
    }

    public function logCompleted()
    {
        $this->logChange('finished', $this);
    }
}
