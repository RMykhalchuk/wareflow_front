<?php

namespace App\Models\Entities\Container;


use App\Models\Entities\Company\Company;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Traits\CompanySeparation;
use App\Traits\FilterByCompany;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Container extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use FilterByCompany;
    use CompanySeparation;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<ContainerType>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ContainerType::class, 'type_id');
    }

    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id');
    }

    public function registers(): HasMany
    {
        return $this->hasMany(ContainerRegister::class, 'container_id', 'id');
    }
}
