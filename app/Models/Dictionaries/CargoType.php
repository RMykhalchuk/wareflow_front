<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class CargoType extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_cargo_types';

    protected $guarded = [];
    public $timestamps = false;

    public $translatable = ['name'];
}
