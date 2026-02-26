<?php

namespace App\Models\Entities\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Region extends Model
{
    use HasFactory;

    protected $table = '_d_regions';

    public $timestamps = false;

    protected $guarded = [];
}
