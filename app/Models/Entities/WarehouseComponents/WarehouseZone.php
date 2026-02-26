<?php

namespace App\Models\Entities\WarehouseComponents;

use App\Models\Entities\WarehouseComponents\Zone\ZoneSubtype;
use App\Models\Entities\WarehouseComponents\Zone\ZoneType;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\Warehouse\ZoneDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseZone extends Model
{
    use HasUuid;
    use HasLocalId;
    use ZoneDelete;
    use CompanySeparation;
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function sectors()
    {
        return $this->hasMany(Sector::class,'zone_id', 'id')->orderBy('name');
    }

    public function cells()
    {
        return $this->morphMany(Cell::class, 'model', 'parent_type', 'model_id');
    }

    /**
     * @return BelongsTo
     */
    public function zoneType(): BelongsTo
    {
        return $this->belongsTo(ZoneType::class, 'zone_type', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function zoneSubtype(): BelongsTo
    {
        return $this->belongsTo(ZoneSubtype::class, 'zone_subtype', 'id');
    }
}
