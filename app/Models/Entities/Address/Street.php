<?php

namespace App\Models\Entities\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Street extends Model
{
    use HasFactory;

    protected $table = '_d_streets';

    protected $guarded = [];
}
