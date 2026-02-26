<?php

namespace App\Models\Entities\Container;

use App\Enums\ContainerRegister\ContainerRegisterStatus;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerRegister extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'status_id' => ContainerRegisterStatus::class,
    ];

    public function scopeInCurrentWarehouse($query): void
    {
        $query->whereHas('cell', function ($q) {
            $q->inCurrentWarehouse();
        });
    }

    public function leftovers(): HasMany
    {
        return $this->hasMany(Leftover::class, 'container_id', 'id');
    }

    public function cell(): HasOne
    {
        return $this->hasOne(Cell::class, 'id', 'cell_id');
    }

    public function container(): HasOne
    {
        return $this->hasOne(Container::class, 'id', 'container_id');
    }


    public function getLoadWeightAttribute(): string
    {
        [$weight, $maxWeight] = $this->loadWeightToArray();

        return $weight . '/' . $maxWeight;
    }

    public function loadWeightToArray()
    {
        $leftovers = $this->leftovers()->with('goods')->get();

        $weight = $leftovers->sum(fn($l) => optional($l->goods)->weight_brutto ?? 0);

        return [$weight, $this->container->max_weight];
    }

    public function getStatusAttribute()
    {
        return $this->status_id->toArray();
    }
}
