<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class DocumentStatus extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_document_statuses';

    public $timestamps = false;

    public $translatable = ['name'];
}
