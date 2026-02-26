<?php

namespace App\Models\Entities\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class Country extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_countries';

    protected $guarded = [];

    public $timestamps = false;

    public $translatable = ['name'];
}
