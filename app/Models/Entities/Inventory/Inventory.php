<?php

namespace App\Models\Entities\Inventory;

use App\Models\Entities\Categories;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Goods\Goods;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\Entities\WarehouseComponents\Row;
use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseErp;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

/**
 * Psalm fix for HasFactory generic:
 * @use HasFactory<Inventory>
 */
class Inventory extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    /**
     * @var string
     */
    protected $table = 'inventories';

    /** UUID PK */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Type full.
     */
    public const TYPE_FULL   = 'full';

    /**
     * Type partly.
     */
    public const TYPE_PARTLY = 'partly';

    /**
     * Process cells statuses.
     */
    public const PROCESS_CELL_ALL   = 1;
    public const PROCESS_CELL_EMPTY = 2;
    public const PROCESS_CELL_FULL  = 3;

    /**
     * Process cells links.
     */
    public const PROCESS_CELLS = [
        self::PROCESS_CELL_ALL   => 'localization.inventory.view.place.all',
        self::PROCESS_CELL_EMPTY => 'localization.inventory.view.place.only_empty',
        self::PROCESS_CELL_FULL  => 'localization.inventory.view.place.only_full',
    ];

    /**
     * Status statuses.
     */
    public const STATUS_CREATED              = 1;
    public const STATUS_IN_PROGRESS          = 2;
    public const STATUS_PAUSED               = 3;
    public const STATUS_FINISHED             = 4;
    public const STATUS_IN_PROGRESS_ANIMAL   = 5;
    public const STATUS_FINISHED_BEFORE      = 6;
    public const STATUS_CANCELLED            = 7;
    public const STATUS_PENDING              = 8;

    /**
     * Status links.
     */
    public const STATUSES = [
        self::STATUS_CREATED            => 'localization.inventory.view.statuses.created',
        self::STATUS_IN_PROGRESS        => 'localization.inventory.view.statuses.in_progress',
        self::STATUS_PAUSED             => 'localization.inventory.view.statuses.paused',
        self::STATUS_FINISHED           => 'localization.inventory.view.statuses.finished',
        self::STATUS_IN_PROGRESS_ANIMAL => 'localization.inventory.view.statuses.in_progress_an_animal',
        self::STATUS_FINISHED_BEFORE    => 'localization.inventory.view.statuses.finished_before',
        self::STATUS_CANCELLED          => 'localization.inventory.view.statuses.cancelled',
        self::STATUS_PENDING            => 'localization.inventory.view.statuses.pending',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'show_leftovers',
        'restrict_goods_movement',
        'process_cell',
        'type',
        'creator_id',
        'performer_id',
        'warehouse_id',
        'warehouse_erp_id',
        'zone_id',
        'sector_id',
        'row_id',
        'cell_id',
        'category_subcategory',
        'manufacturer_id',
        'supplier_id',
        'nomenclature_id',
        'start_date',
        'end_date',
        'comment',
        'status',
        'priority',
        'brand'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'show_leftovers'           => 'bool',
        'restrict_goods_movement'  => 'bool',
        'process_cell'             => 'int',
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'cell_id'              => 'string',
        'status' => 'int',
        'priority' => 'integer',
    ];

    /**
     * @var string[]
     */
    protected $attributes = [
        'type' => self::TYPE_FULL,
        'status' => self::STATUS_CREATED,
        'process_cell' => self::PROCESS_CELL_ALL
    ];

    /**
     * @return string
     */
    public function getProcessCellLabelAttribute(): string
    {
        $key = self::PROCESS_CELLS[$this->process_cell] ?? null;

        return $key && Lang::has($key) ? __($key) : 'Unknown';
    }

    /**
     * @return bool
     */
    public function processesAllCells(): bool
    {
        return $this->process_cell === self::PROCESS_CELL_ALL;
    }

    /**
     * @return bool
     */
    public function processesOnlyEmpty(): bool
    {
        return $this->process_cell === self::PROCESS_CELL_EMPTY;
    }

    /**
     * @return bool
     */
    public function processesOnlyFull(): bool
    {
        return $this->process_cell === self::PROCESS_CELL_FULL;
    }

    /**
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $key = self::STATUSES[$this->status] ?? null;

        return $key && Lang::has($key) ? __($key) : 'Unknown';
    }

    /**
     * @return bool
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return BelongsTo
     */
    public function warehouseErp(): BelongsTo
    {
        return $this->belongsTo(WarehouseErp::class, 'warehouse_erp_id');
    }

    /**
     * @return BelongsTo
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(WarehouseZone::class, 'zone_id');
    }

    /**
     * @return BelongsTo
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    /**
     * @return BelongsTo
     */
    public function row(): BelongsTo
    {
        return $this->belongsTo(Row::class, 'row_id');
    }

    /**
     * @return BelongsTo
     */
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class, 'cell_id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_subcategory');
    }

    /**
     * @return BelongsTo
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'manufacturer_id');
    }

    /**
     * @return BelongsTo
     */
    public function brandData(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'brand', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performer_id');
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'supplier_id');
    }

    /**
     * @return BelongsTo
     */
    public function nomenclature(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'nomenclature_id');
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'inventory_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function performers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'inventory_performers',
            'inventory_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * @return array
     */
    public function getItemsData(): array
    {
        $row = DB::table('inventory_items as ii')
            ->selectRaw('COUNT(*)::int as total')
            ->selectRaw('SUM(CASE WHEN ii.status = 2 THEN 1 ELSE 0 END)::int as finished')
            ->where('ii.inventory_id', $this->id)
            ->first();

        $total    = (int) ($row->total ?? 0);
        $finished = (int) ($row->finished ?? 0);
        $percent  = $total > 0 ? round(($finished / $total) * 100, 2) : 0.0;

        return [
            'total'    => $total,
            'finished' => $finished,
            'progress' => $total - $finished,
            'percent'  => $percent,
        ];
    }

    /**
     * @return Attribute
     */
    protected function startDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => blank($value) ? null : $value,
        );
    }

    /**
     * @return Attribute
     */
    protected function endDate(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => blank($value) ? null : $value,
        );
    }

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(
            Goods::class,
            'inventory_goods',
            'inventory_id',
            'goods_id'
        )->withTimestamps();
    }
}
