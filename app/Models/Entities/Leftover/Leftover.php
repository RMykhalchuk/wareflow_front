<?php

namespace App\Models\Entities\Leftover;

use App\Enums\Leftovers\LeftoversStatus;
use App\Models\Entities\Container\ContainerRegister;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Inventory\InventoryItem;
use App\Models\Entities\Inventory\InventoryManualLeftoverLog;
use App\Models\Entities\Logs\EntityLog;
use App\Models\Entities\Package;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Leftover.
 */
final class Leftover extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;
    use LogActivity;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $casts   = [
        'status_id' => LeftoversStatus::class,
        'manufacture_date'  => 'date:Y-m-d',
        'bb_date'           => 'date:Y-m-d',
    ];
    protected $guarded = ['id'];

    public function scopeInCurrentWarehouse($query): void
    {
        $query->whereHas('cell', function ($q) {
            $q->inCurrentWarehouse();
        });
    }

    /**
     * @return BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    /**
     * @return BelongsTo
     */
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id');
    }

    /**
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * @return BelongsTo
     */
    public function container(): BelongsTo
    {
        return $this->belongsTo(ContainerRegister::class, 'container_id');
    }

    /**
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    // Leftover.php

    /**
     * @return HasMany
     */
    public function goodsToContainers(): HasMany
    {
        return $this->hasMany(LeftoverToContainerRegister::class, 'leftover_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'leftover_id', 'id');
    }

    /**
     * @return array
     */
    public function getStatusAttribute(): array
    {
        $status = $this->status_id;

        return $status->toArray();
    }

    /**
     * @return HasMany
     */
    public function manualLogs(): HasMany
    {
        return $this->hasMany(InventoryManualLeftoverLog::class, 'leftover_id');
    }

    /**
     * Latest manual change log (by created_at, id).
     */
    public function latestManualLog(): HasOne
    {
        return $this->hasOne(InventoryManualLeftoverLog::class, 'leftover_id')
            ->latest('created_at')
            ->latest('id');
    }

    /**
     * @return Leftover|null
     */
    public function getChildAttribute(): ?Leftover
    {
        return Leftover::where('parent_id', $this->id)
            ->first();
    }

    /**
     * @return Leftover|null
     */
    public function getParentAttribute(): ?Leftover
    {
        return Leftover::where('child_id', $this->id)
            ->first();
    }

    /**
     * @return HasOne
     */
    public function createdLog()
    {
        return $this->hasOne(EntityLog::class, 'model_id', 'id')
            ->where('model_type', self::class)
            ->where('log_type', 'created');
    }
}
