<?php

namespace App\Models\Entities\WarehouseComponents;

use App\Enums\Cells\CellStatus;
use App\Models\Entities\Leftover\Leftover;
use App\Traits\HasUuid;
use App\Traits\Warehouse\CellDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class Cell extends Model
{
    use CellDelete;
    use HasUuid;
    use SoftDeletes;

    public static string $searchField = 'code';

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $appends = ['allocation']; // щоб це завжди підвантажувалося в JSON

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'status' => CellStatus::class,
    ];


    public function scopeInCurrentWarehouse($query): void
    {
        $warehouseId = Auth::user()->currentWarehouseId;

        $query->where(function ($q) use ($warehouseId) {
            $q->where(function ($q) use ($warehouseId) {
                $q->where('parent_type', 'zone')
                    ->whereIn('model_id', function ($subQuery) use ($warehouseId) {
                        $subQuery->select('id')
                            ->from('warehouse_zones')
                            ->where('warehouse_id', $warehouseId);
                    });
            })
                ->orWhere(function ($q) use ($warehouseId) {
                    $q->where('parent_type', 'sector')
                        ->whereIn('model_id', function ($subQuery) use ($warehouseId) {
                            $subQuery->select('id')
                                ->from('sectors')
                                ->whereIn('zone_id', function ($zoneQuery) use ($warehouseId) {
                                    $zoneQuery->select('id')
                                        ->from('warehouse_zones')
                                        ->where('warehouse_id', $warehouseId);
                                });
                        });
                })
                ->orWhere(function ($q) use ($warehouseId) {
                    $q->where('parent_type', 'row')
                        ->whereIn('model_id', function ($subQuery) use ($warehouseId) {
                            $subQuery->select('id')
                                ->from('rows')
                                ->whereIn('sector_id', function ($sectorQuery) use ($warehouseId) {
                                    $sectorQuery->select('id')
                                        ->from('sectors')
                                        ->whereIn('zone_id', function ($zoneQuery) use ($warehouseId) {
                                            $zoneQuery->select('id')
                                                ->from('warehouse_zones')
                                                ->where('warehouse_id', $warehouseId);
                                        });
                                });
                        });
                });
        });
    }

    /**
     * @return array
     */
    public function getAllocationAttribute(): array
    {
        return match ($this->parent_type) {
            'row' => $this->fromRow(),
            'sector' => $this->fromSector(),
            'zone' => $this->fromZone(),
            default => [],
        };
    }

    /**
     * @return array
     */
    private function fromRow(): array
    {
        $sector = $this->parent?->sector?->loadMissing('zone.warehouse.location');

        return [
            'zone' => $sector?->zone?->name,
            'sector' => $sector?->name,
            'sector_code' => $sector?->code,
            'warehouse' => $sector?->zone?->warehouse?->name,
            'warehouse_id' => $sector?->zone?->warehouse?->id,
            'location' => $sector?->zone?->warehouse?->location?->name,
            'cell' => $this->code,
        ];
    }

    /**
     * @return array
     */
    private function fromSector(): array
    {
        $sector = $this->parent?->loadMissing('zone.warehouse.location');

        return [
            'zone' => $sector?->zone?->name,
            'sector' => $sector?->name,
            'sector_code' => $sector?->code,
            'warehouse' => $sector?->zone?->warehouse?->name,
            'warehouse_id' => $sector?->zone?->warehouse?->id,
            'location' => $sector?->zone?->warehouse?->location?->name,
            'cell' => $this->code,
        ];
    }

    /**
     * @return array
     */
    private function fromZone(): array
    {
        $zone = $this->parent?->loadMissing('warehouse.location');

        return [
            'zone' => $zone?->name,
            'sector' => null,
            'warehouse' => $zone?->warehouse?->name,
            'warehouse_id' => $zone?->warehouse?->id,
            'location' => $zone?->warehouse?->location?->name,
            'cell' => $this->code,
        ];
    }


    /**
     * @return string
     */
    public function allocationToString(): string
    {
        $allocation = $this->allocation;

        return collect(
            [
                $allocation['location'],
                $allocation['warehouse'],
                $allocation['sector'],
                $allocation['cell'],
            ]
        )->filter()->implode(' - ');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leftovers()
    {
        return $this->hasMany(Leftover::class, 'cell_id', 'id');
    }

    /**
     * @return MorphTo
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'parent_type', 'model_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rowInfo()
    {
        return $this->hasOne(RowCellInfo::class);
    }

    /**
     * @return string|null
     */
    public function getWarehouseId(): ?string
    {
        $allocation = $this->getAllocationAttribute();
        return $allocation['warehouse_id'] ?? null;
    }

    /**
     * @param $cellIds
     * @return Collection|null
     */
    public static function getWarehouseMapByCellIds($cellIds): ?Collection
    {
        $cells = self::whereIn('id', $cellIds)
            ->with([
                       'parent' => function (MorphTo $morphTo) {
                           $morphTo->morphWith([
                                                   WarehouseZone::class => ['warehouse.location'],
                                                   Sector::class => ['zone.warehouse.location'],
                                                   Row::class => ['sector.zone.warehouse.location'],
                                               ]);
                       }
                   ])
            ->get();

        // map [cell_id => warehouse_id]
        return $cells->mapWithKeys(function (self $oneCell) {
            $warehouseId = $oneCell->getWarehouseId();

            return [$oneCell->id => $warehouseId];
        });
    }
}
