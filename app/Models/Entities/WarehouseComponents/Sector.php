<?php

namespace App\Models\Entities\WarehouseComponents;

use App\Traits\HasUuid;
use App\Traits\Warehouse\SectorDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Sector.
 */
final class Sector extends Model
{
    use HasUuid;
    use SectorDelete;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    /**
     * @return HasOne
     */
    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    /**
     * @return BelongsTo
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(WarehouseZone::class, 'zone_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function rows(): HasMany
    {
        return $this->hasMany(Row::class, 'sector_id', 'id')
            ->orderByRaw("CAST(SUBSTRING(name FROM '\\d+') AS INTEGER)")
            ->orderBy('name');
    }

    /**
     * @return MorphMany
     */
    public function cells(): MorphMany
    {
        return $this->morphMany(Cell::class, 'model', 'parent_type', 'model_id');
    }
}
