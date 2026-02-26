<?php

namespace App\Models\Entities\WarehouseComponents;

use App\Traits\HasUuid;
use App\Traits\Warehouse\RowDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Row extends Model
{
    use HasUuid;
    use RowDelete;
    use SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';
    protected $guarded = [];

    protected $casts = [
        'cell_props' => 'array'
    ];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id', 'id');
    }


    public function cells()
    {
        return $this->morphMany(Cell::class, 'model', 'parent_type', 'model_id');
    }
}
