<?php

namespace App\Models\Dictionaries;

use App\Models\Entities\Categories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

final class GoodsCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $table = '_d_goods_categories';


    public $timestamps = false;

    protected $fillable = ['parent_id', 'name'];

    public $translatable = ['name'];


    /**
     * @psalm-return HasMany<Model>
     */
    public function children(): HasMany
    {
        return $this->hasMany('App\Models\Dictionaries\GoodsCategory', 'parent_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Categories::class, 'goods_category_id');
    }

    public function isChild(): bool
    {
        return $this->parent_id != null;
    }
}
