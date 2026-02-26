<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class DownloadZone extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_download_zones';

    public $timestamps = false;

    protected $guarded = [];

    public $translatable = ['name'];
}
