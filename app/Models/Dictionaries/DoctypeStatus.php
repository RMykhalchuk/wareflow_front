<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class DoctypeStatus extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_doctype_statuses';

    public $translatable = ['name'];
}
