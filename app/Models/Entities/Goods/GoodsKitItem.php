<?php

namespace App\Models\Entities\Goods;

use App\Models\Entities\Package;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsKitItem extends Model
{
    use SoftDeletes;
    use HasUuid;
    protected $table = 'goods_kit_items';

    protected $fillable = [
        'goods_id',
        'package_id',
        'quantity',
        'goods_parent_id',
    ];

    public function parentKit(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'good_parent_id');
    }

    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    public function packages(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
