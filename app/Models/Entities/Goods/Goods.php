<?php

namespace App\Models\Entities\Goods;

use App\Enums\Goods\GoodsStatus;
use App\Interfaces\StoreImageInterface;
use App\Models\Dictionaries\Adr;
use App\Models\Dictionaries\CargoType;
use App\Models\Dictionaries\MeasurementUnit;
use App\Models\Entities\Address\Country;
use App\Models\Entities\Barcode;
use App\Models\Entities\Categories;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Leftover\Leftover;
use App\Models\Entities\Inventory\Inventory;
use App\Models\Entities\LeftoverErp\LeftoverErp;
use App\Models\Entities\Package;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Goods extends Model
{
    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'expiration_date' => 'array',
        'status_id'       => GoodsStatus::class,
    ];

    protected function expirationDate(): Attribute
    {
        return Attribute::make(
            set: fn($value) => is_array($value) ? json_encode($value) : $value
        );
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function cargo_type(): BelongsTo
    {
        return $this->belongsTo(CargoType::class, 'cargo_type_id');
    }

    public function manufacturer_country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'manufacturer_country_id');
    }

    public function adr(): BelongsTo
    {
        return $this->belongsTo(Adr::class, 'adr_id');
    }

    public function measurement_unit(): BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class, 'measurement_unit_id');
    }

    public function barcodes(): MorphMany
    {
        return $this->morphMany(Barcode::class, 'entity');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'goods_id');
    }

    public function goodsKitItems(): HasMany
    {
        return $this->hasMany(GoodsKitItem::class, 'goods_parent_id');
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }

    public function leftovers(): HasMany
    {
        return $this->hasMany(Leftover::class, 'goods_id');
    }

    public function leftoversErp(): HasMany
    {
        return $this->hasMany(LeftoverErp::class, 'goods_erp_id', 'erp_id');
    }

    /**
     * Provider company (column: provider, now UUID -> companies.id)
     */
    public function providerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'provider');
    }

    /**
     * Manufacturer company (column: manufacturer, now UUID -> companies.id)
     */
    public function manufacturerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'manufacturer');
    }

    /**
     * Brand company (column: brand, now UUID -> companies.id)
     */
    public function brandCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'brand');
    }

    public function scopeWithLeftoversTotals($query)
    {
        return $query
            ->withSum('leftovers as leftovers_wms_total', 'quantity')
            ->withSum(['leftoversErp as leftovers_erp_total' => function ($q) {
            }], 'quantity');
    }

    public function getStatusAttribute(): array
    {
        $status = $this->status_id;

        return $status->toArray();
    }

    public function storeImage($request): void
    {
        if ($request->file('image')) {
            $imageService = resolve(StoreImageInterface::class);
            $imageService->setImage($request, $this, 'goods');
        }
    }

    public function hasLeftovers()
    {
        return Leftover::where('goods_id', $this->id)->exists();
    }

    public function getMainPackageBarcode()
    {
        $package = Package::where('goods_id', $this->id)->whereNull('parent_id')->first();

        return $package->barcodeString;
    }

    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(
            Inventory::class,
            'inventory_goods',
            'goods_id',
            'inventory_id'
        )->withTimestamps();
    }
}
