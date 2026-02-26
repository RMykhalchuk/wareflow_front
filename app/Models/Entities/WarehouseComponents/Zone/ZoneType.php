<?php

namespace App\Models\Entities\WarehouseComponents\Zone;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

/**
 * ZoneType.
 */
class ZoneType extends Model
{
    use HasTranslations;

    protected $table = '_d_zone_types';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'key',
        'name',
    ];

    protected $appends = [
        'translated_name',
    ];

    protected $casts = [
        'name' => 'array',
    ];


    public $translatable = ['name'];

    /**
     * @return string
     */
    public function getTranslatedNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * @return BelongsToMany
     */
    public function subtypes(): BelongsToMany
    {
        return $this->belongsToMany(
            ZoneSubtype::class,
            '_d_zone_type_subtype',
            'zone_type_id',
            'zone_subtype_id'
        );
    }
}
