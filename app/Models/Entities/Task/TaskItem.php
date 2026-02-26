<?php

namespace App\Models\Entities\Task;

use Illuminate\Database\Eloquent\Model;

class TaskItem extends Model
{

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'main_unit_quantity' => 'decimal:3',
        'packing_quantity' => 'decimal:3',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
