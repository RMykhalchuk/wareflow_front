<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class StorageType extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_storage_types';

    protected $guarded = [];

    public $translatable = ['name'];
}
