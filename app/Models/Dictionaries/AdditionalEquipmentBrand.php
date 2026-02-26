<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class AdditionalEquipmentBrand extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_additional_equipment_brands';

    protected $guarded = [];
    public $timestamps = false;

    public $translatable = ['name'];
    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<AdditionalEquipmentModel>
     */
    public function models(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdditionalEquipmentModel::class, 'brand_id', 'id');
    }
}
