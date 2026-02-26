<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class LegalType extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_legal_types';

    protected $guarded = [];

    public $timestamps = false;

    protected $appends = [
        'label',
    ];

    public $translatable = ['name'];

    public function getLabelAttribute(): string
    {
        $translated = __("localization.{$this->key}");

        if ($translated === $this->key) {
            return $this->name ?? $this->key;
        }

        return $translated;
    }
}
