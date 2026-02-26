<?php

namespace App\Models\Entities\Inventory;

use App\Models\Entities\Goods\Goods;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Package;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property string        $id
 * @property string        $inventory_item_id
 * @property string|null   $leftover_id
 * @property string        $goods_id
 * @property string        $package_id
 * @property int           $quantity
 * @property string        $batch
 * @property Carbon $manufacture_date
 * @property Carbon $bb_date
 * @property int           $source_type    1=existing, 2=new
 * @property string        $creator_id
 * @property Carbon|null $approved_at
 *
 * @property-read InventoryItem $inventoryItem
 * @property-read Leftover|null $leftover
 * @property-read Goods         $goods
 * @property-read Package       $package
 * @property-read User          $creator
 */
class InventoryLeftover extends Model
{
    use HasFactory, HasUuids;

    public const SOURCE_EXISTING = 1;
    public const SOURCE_NEW      = 2;

    protected $table = 'inventory_leftovers';

    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string[]
     */
    protected $fillable = [
        'inventory_item_id',
        'leftover_id',
        'goods_id',
        'package_id',
        'quantity',
        'batch',
        'manufacture_date',
        'bb_date',
        'source_type',
        'creator_id',
        'approved_at',
        'current_leftovers',
        'expiration_term',
        'container_registers_id',
        'condition',
        'area'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'quantity'         => 'integer',
        'manufacture_date'  => 'date:Y-m-d',
        'bb_date'           => 'date:Y-m-d',
        'approved_at'      => 'datetime',
        'source_type'      => 'integer',
        'expiration_term'  => 'integer',
        'current_leftovers' => 'integer',
        'condition' => 'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function leftover(): BelongsTo
    {
        return $this->belongsTo(Leftover::class, 'leftover_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeExisting($query): mixed
    {
        return $query->where('source_type', self::SOURCE_EXISTING);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeNewlyCreated($query): mixed
    {
        return $query->where('source_type', self::SOURCE_NEW);
    }

    /**
     * @return bool
     */
    public function isExisting(): bool
    {
        return (int) $this->source_type === self::SOURCE_EXISTING && !is_null($this->leftover_id);
    }

    /**
     * @return bool
     */
    public function isNewlyCreated(): bool
    {
        return (int) $this->source_type === self::SOURCE_NEW && is_null($this->leftover_id);
    }

    /**
     * @return string|null
     */
    public function getAreaLabel(): ?string
    {
        return match ($this->area) {
            'web' => __("localization.inventory.area.{$this->area}"),
            'api' => __("localization.inventory.area.{$this->area}"),
            default => null,
        };
    }

    /**
     * @param InventoryLeftover $leftover
     * @return Collection
     */
    public function calculatePackage(InventoryLeftover $leftover)
    {
        $current = Package::find($leftover->package_id);
        if (!$current) {
            return collect();
        }

        $chain = collect([$current]);

        $this->appendChildren($chain, $current);

        $chain->each(function (Package $package) use ($leftover) {
            $package->quantity = $leftover->quantity;
        });

        return $chain;
    }


    /**
     * @param Collection $chain
     * @param Package $package
     * @return void
     */
    private function appendChildren(Collection &$chain, Package $package): void
    {
        $child = Package::where('parent_id', $package->id)->first();

        if ($child) {
            $chain->push($child);
            $this->appendChildren($chain, $child);
        }
    }
}
