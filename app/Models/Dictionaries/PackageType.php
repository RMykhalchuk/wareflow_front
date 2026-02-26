<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

/**
 * PackageType.
 */
final class PackageType extends Model
{
    use HasFactory, HasTranslations;

    /**
     * @var string
     */
    protected $table = '_d_package_types';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'key',
        'name',
    ];

    public $translatable = ['name'];

    /**
     * @return string
     */
    public function getLabelAttribute(): string
    {
        $translationKey = 'localization.package_types.' . $this->key;

        $translated = __($translationKey);

        return $translated === $translationKey
            ? $this->name
            : $translated;
    }
}
