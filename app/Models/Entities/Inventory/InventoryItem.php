<?php

namespace App\Models\Entities\Inventory;

use App\Enums\Inventory\InventoryItemStatus;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\WarehouseComponents\Cell;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string        $id
 * @property string        $inventory_id
 * @property string        $cell_id
 * @property string        $creator_id
 * @property string|null   $update_id
 * @property int           $qty
 * @property int|null      $real_qty
 *
 * @property-read Inventory         $inventory
 * @property-read Cell              $cell
 * @property-read User              $creator
 * @property-read User|null         $updater
 * @property-read InventoryLeftover[] $inventoryLeftovers
 */
class InventoryItem extends Model
{
    use HasFactory, HasUuids;

    /**
     * @var string
     */
    protected $table = 'inventory_items';

    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'inventory_id',
        'cell_id',
        'qty',
        'real_qty',
        'creator_id',
        'update_id',
        'status',
        'area'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'qty' => 'integer',
        'real_qty' => 'integer',
        'status' => InventoryItemStatus::class,
    ];

    /**
     * @var array
     */
    protected $attributes = [
        'status' => InventoryItemStatus::BEFORE_INVENTORY,
    ];

    /**
     * @return BelongsTo
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * @return BelongsTo
     */
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class);
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'update_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function inventoryLeftovers(): HasMany
    {
        return $this->hasMany(InventoryLeftover::class, 'inventory_item_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function leftovers(): BelongsToMany
    {
        return $this->belongsToMany(
            Leftover::class,
            'inventory_leftovers',
            'inventory_item_id',
            'leftover_id'
        )->withTimestamps();
    }

    /**
     * @param $query
     * @param string $inventoryId
     * @return mixed
     */
    public function scopeForInventory($query, string $inventoryId): mixed
    {
        return $query->where('inventory_id', $inventoryId);
    }

    /**
     * @param $query
     * @param string $cellId
     * @return mixed
     */
    public function scopeForCell($query, string $cellId): mixed
    {
        return $query->where('cell_id', $cellId);
    }

    /**
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeBeforeInventory($q): mixed
    {
        return $q->where('status', InventoryItemStatus::BEFORE_INVENTORY);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeInventoried($q): mixed
    {
        return $q->where('status', InventoryItemStatus::INVENTORIED);
    }

    /**
     * @return string|null
     */
    public function getAreaLabel(): ?string
    {
        return in_array($this->area, ['web', 'api'], true)
            ? __("localization.inventory.area.$this->area")
            : null;
    }
}
