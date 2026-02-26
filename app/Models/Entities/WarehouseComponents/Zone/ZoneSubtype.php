<?php

namespace App\Models\Entities\WarehouseComponents\Zone;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

/**
 * ZoneSubtype.
 */
class ZoneSubtype extends Model
{
    use HasTranslations;
    protected $table = '_d_zone_subtypes';

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
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(
            ZoneType::class,
            '_d_zone_type_subtype',
            'zone_subtype_id',
            'zone_type_id'
        );
    }
}
