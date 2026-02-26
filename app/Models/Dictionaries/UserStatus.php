<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class UserStatus extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_user_statuses';

    public $timestamps = false;

    protected $guarded = [];

    public $translatable = ['name'];
}
