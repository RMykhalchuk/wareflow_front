<?php

namespace App\Models\Entities\Container;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class ContainerType extends Model
{
    use HasTranslations;

    protected $table = '_d_container_types';

    protected $guarded = [];

    public $translatable = ['name'];
}
