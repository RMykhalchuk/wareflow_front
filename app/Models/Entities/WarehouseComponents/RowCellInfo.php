<?php

namespace App\Models\Entities\WarehouseComponents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RowCellInfo extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $table = 'row_cell_info';

    protected $guarded = [];

    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }
}
