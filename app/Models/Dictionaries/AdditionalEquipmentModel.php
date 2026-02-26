<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class AdditionalEquipmentModel extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_additional_equipment_models';

    public $timestamps = false;
    protected $guarded = [];

    public $translatable = ['name'];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<AdditionalEquipmentBrand>
     */
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdditionalEquipmentBrand::class, 'brand_id');
    }
}
