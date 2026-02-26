<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Model;

final class CellStatus extends Model
{
    protected $table = '_d_cell_statuses';
    public $timestamps = false;
    protected $guarded = [];
}
