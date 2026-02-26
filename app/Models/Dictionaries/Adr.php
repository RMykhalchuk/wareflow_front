<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class Adr extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_adrs';

    protected $guarded = [];

    public $timestamps = false;

    public $translatable = ['name'];
}
