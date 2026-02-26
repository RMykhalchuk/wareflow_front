<?php

namespace App\Models\Entities\Document;

use Illuminate\Database\Eloquent\Model;

class DoctypeStructure extends Model
{
    protected $casts = [
        'settings' => 'array',
    ];

    protected $guarded = [];

    protected $table = 'doctype_structure';

    public $timestamps = false;


}
