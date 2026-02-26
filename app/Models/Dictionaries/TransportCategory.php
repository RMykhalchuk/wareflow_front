<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


final class TransportCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_transport_categories';

    public $timestamps = false;
    protected $guarded = [];

    public $translatable = ['name'];
}
