<?php

namespace App\Models\Entities\LeftoverErp;

use App\Models\Entities\Goods\Goods;
use App\Models\Entities\WarehouseComponents\WarehouseErp;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Leftover erp.
 */
final class LeftoverErp extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;
    use LogActivity;

    protected $table = 'leftovers_erp';

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'quantity' => 'decimal:3'
    ];

    /**
     * @return BelongsTo
     */
    public function warehouseErp(): BelongsTo
    {
        return $this->belongsTo(WarehouseErp::class, 'warehouse_erp_id','id_erp');
    }

    /**
     * @return BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_erp_id','erp_id');
    }
}
