<?php

declare(strict_types=1);

namespace App\Models\Entities\Inventory;

use App\Models\Entities\Leftover\Leftover;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Inventory manual change log for leftovers.
 *
 * Columns:
 *  - id               (bigint, PK)
 *  - leftover_id      (uuid, FK -> leftovers.id)
 *  - quantity_before  (decimal(18,3)|null)
 *  - quantity_after   (decimal(18,3)|null)
 *  - area             (varchar(64)|null)
 *  - executor_id      (uuid|null)
 *  - group_id         (uuid|null)
 *  - group_type       (varchar(64)|null)
 *  - created_at       (timestamp)
 *
 * @property-read string      $id
 * @property string           $leftover_id
 * @property string|null      $executor_id
 * @property string|null      $quantity_before
 * @property string|null      $quantity_after
 * @property string|null      $area
 * @property string|null      $group_id
 * @property string|null      $group_type
 * @property Carbon           $created_at
 *
 * @property-read Leftover    $leftover
 * @property-read User|null   $executor
 *
 * @property-read string|null $changed_quantity
 */
class InventoryManualLeftoverLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'inventory_manual_leftover_logs';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @var string[]
     */
    protected $casts = [
        'leftover_id'     => 'string',
        'executor_id'     => 'string',
        'group_id'        => 'string',
        'group_type'      => 'string',
        'quantity_before' => 'decimal:3',
        'quantity_after'  => 'decimal:3',
        'created_at'      => 'immutable_datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function leftover(): BelongsTo
    {
        return $this->belongsTo(Leftover::class, 'leftover_id');
    }

    /**
     * @return BelongsTo
     */
    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executor_id');
    }

    /**
     * @return string|null
     */
    public function getChangedQuantityAttribute(): ?string
    {
        if ($this->quantity_before === null || $this->quantity_after === null) {
            return null;
        }

        $before = (string) $this->quantity_before;
        $after  = (string) $this->quantity_after;

        if (function_exists('bcsub')) {
            return bcsub($after, $before, 3);
        }

        $diff = (float) $after - (float) $before;

        return number_format($diff, 3, '.', '');
    }

    /**
     * @param $q
     * @param string $leftoverId
     * @return mixed
     */
    public function scopeForLeftover($q, string $leftoverId): mixed
    {
        return $q->where('leftover_id', $leftoverId);
    }

    /**
     * @param $q
     * @param string|null $executorId
     * @return mixed
     */
    public function scopeByExecutor($q, ?string $executorId): mixed
    {
        return $executorId ? $q->where('executor_id', $executorId) : $q;
    }

    /**
     * @param $q
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return mixed
     */
    public function scopeBetween($q, ?Carbon $from, ?Carbon $to): mixed
    {
        if ($from) $q->where('created_at', '>=', $from->toDateTimeString());
        if ($to)   $q->where('created_at', '<=', $to->toDateTimeString());

        return $q;
    }

    /**
     * @param $q
     * @return mixed
     */
    public function scopeLatestFirst($q): mixed
    {
        return $q->orderByDesc('created_at')->orderByDesc('id');
    }

    /**
     * Record a leftover change.
     *
     * @param string $leftoverId
     * @param string|float|null $qtyBefore decimal scale 3 preferred (string)
     * @param string|float|null $qtyAfter decimal scale 3 preferred (string)
     * @param string|null $area current/target area (single field)
     * @param string|null $executorId uuid of executor (optional)
     * @param Carbon|null $at event time; defaults to now()
     * @param string|null $groupId
     * @param string|null $groupType
     * @return InventoryManualLeftoverLog
     */
    public static function recordChange(
        string $leftoverId,
        string|float|null $qtyBefore,
        string|float|null $qtyAfter,
        ?string $area,
        ?string $executorId = null,
        ?Carbon $at = null,
        ?string $groupId = null,
        ?string $groupType = null
    ): self {
        $toDec = static function ($v): ?string {
            if ($v === null) return null;
            if (is_string($v)) return $v;
            return number_format((float)$v, 3, '.', '');
        };

        return static::create([
            'leftover_id'     => $leftoverId,
            'quantity_before' => $toDec($qtyBefore),
            'quantity_after'  => $toDec($qtyAfter),
            'area'            => $area,
            'executor_id'     => $executorId,
            'group_id'        => $groupId,
            'group_type'      => $groupType,
            'created_at'      => ($at ?? now())->toDateTimeString(),
        ]);
    }
}
