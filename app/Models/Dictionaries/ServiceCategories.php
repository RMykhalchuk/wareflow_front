<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class ServiceCategories extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_service_categories';

    protected $guarded = [];

    public $translatable = ['name'];
}
