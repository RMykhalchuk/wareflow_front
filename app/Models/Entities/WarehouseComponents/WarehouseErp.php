<?php

namespace App\Models\Entities\WarehouseComponents;

use App\Traits\FilterByCompany;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CompanySeparation;

class WarehouseErp extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;
    use FilterByCompany;

    protected $table   = 'warehouses_erp';
    protected $keyType      = 'string';
    public    $incrementing = false;
    protected $guarded = [];

    public function updateData(array $data): WarehouseErp
    {
        $this->update($data);
        return $this;
    }

    public function warehouses()
    {
        return $this->belongsToMany(
            Warehouse::class,
            'warehouse_erp_assignments',
            'warehouse_erp_id',
            'warehouse_id'
        );
    }
}


