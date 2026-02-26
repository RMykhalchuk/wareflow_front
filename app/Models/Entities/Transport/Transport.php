<?php

namespace App\Models\Entities\Transport;

use App\Interfaces\StoreImageInterface;
use App\Models\Dictionaries\Adr;
use App\Models\Dictionaries\TransportBrand;
use App\Models\Dictionaries\TransportCategory;
use App\Models\Dictionaries\TransportDownload;
use App\Models\Dictionaries\TransportModel;
use App\Models\Dictionaries\TransportType;
use App\Models\Entities\Address\Country;
use App\Models\Entities\Company\Company;
use App\Models\User;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\TransportDataTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Transport extends Model
{
    use SoftDeletes;
    use TransportDataTrait;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = [];

    /**
     * @return TransportDownload|\Illuminate\Database\Eloquent\Collection|null
     *
     * @psalm-return TransportDownload|\Illuminate\Database\Eloquent\Collection<int, TransportDownload>|null
     */
    public function getDownloadMethodById($id): \Illuminate\Database\Eloquent\Collection|TransportDownload|null
    {
        return TransportDownload::find($id);
    }
    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransportBrand>
     */
    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportBrand::class, 'brand_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<TransportModel>
     */
    public function model(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportModel::class, 'model_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<TransportCategory>
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TransportCategory::class, 'id', 'category_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<TransportType>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TransportType::class, 'id', 'type_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Company>
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<User>
     */
    public function driver(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'driver_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Country>
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Adr>
     */
    public function adr(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Adr::class, 'id', 'adr_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<AdditionalEquipment>
     */
    public function equipment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AdditionalEquipment::class, 'id', 'equipment_id');
    }

    /**
     * @param \App\Http\Requests\Web\Transport\TruckRequest|\App\Http\Requests\Web\Transport\TruckWithoutTrailer $request
     */
    public function storeImage(\App\Http\Requests\Web\Transport\TruckWithoutTrailer|\App\Http\Requests\Web\Transport\TruckRequest $request): void
    {
        if ($request->file('image')) {
            $imageService = resolve(StoreImageInterface::class);
            $imageService->setImage($request, $this, 'transport');
        }
    }

    public function updateWithoutTrailer(\App\Http\Requests\Web\Transport\TruckWithoutTrailer $request): void
    {
        $this->update([
            'brand_id' => $request->mark,
            'model_id' => $request->model,
            'category_id' => $request->category,
            'type_id' => $request->type,
            'weight' => $request->weight,
            'license_plate' => $request->license_plate ?? $request->license_plate_without_mask,
            'registration_country_id' => $request->registration_country,
            'manufacture_year' => $request->manufacture_year,
            'company_id' => $request->company,
            'driver_id' => $request->driver,
            'spending_empty' => $request->spending_empty,
            'spending_full' => $request->spending_full,
            'equipment_id' => $request->equipment
        ]);

        $this->storeImage($request);
    }

    public function updateWithTrailer(\App\Http\Requests\Web\Transport\TruckRequest $request): void
    {
        $this->update([
            'brand_id' => $request->mark,
            'model_id' => $request->model,
            'category_id' => $request->category,
            'type_id' => $request->type,
            'license_plate' => $request->license_plate ?? $request->license_plate_without_mask,
            'registration_country_id' => $request->registration_country,
            'manufacture_year' => $request->manufacture_year,
            'company_id' => $request->company,
            'driver_id' => $request->driver,
            'carrying_capacity' => $request->carrying_capacity,
            'hydroboard' => $request->hydroboard == 'true',
            'download_methods' => $request->download_methods,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'volume' => $request->volume,
            'weight' => $request->weight,
            'capacity_eu' => $request->capacity_eu,
            'capacity_am' => $request->capacity_am,
            'spending_empty' => $request->spending_empty,
            'spending_full' => $request->spending_full,
            'equipment_id' => $request->equipment,
            'adr_id' => $request->adr
        ]);

        $this->storeImage($request);
    }

    public static function storeWithTrailer(\App\Http\Requests\Web\Transport\TruckRequest $request): void
    {
        $transport = Transport::create([
            'brand_id' => $request->mark,
            'model_id' => $request->model,
            'category_id' => $request->category,
            'type_id' => $request->type,
            'license_plate' => $request->license_plate ?? $request->license_plate_without_mask,
            'registration_country_id' => $request->registration_country,
            'manufacture_year' => $request->manufacture_year,
            'company_id' => $request->company,
            'driver_id' => $request->driver,
            'carrying_capacity' => $request->carrying_capacity,
            'hydroboard' => $request->hydroboard == 'true',
            'download_methods' => $request->download_methods,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'volume' => $request->volume,
            'weight' => $request->weight,
            'capacity_eu' => $request->capacity_eu,
            'capacity_am' => $request->capacity_am,
            'spending_empty' => $request->spending_empty,
            'spending_full' => $request->spending_full,
            'equipment_id' => $request->equimpent,
            'adr_id' => $request->adr
        ]);

        $transport->storeImage($request);
    }

    public static function storeWithoutTrailer(\App\Http\Requests\Web\Transport\TruckWithoutTrailer $request): void
    {

        $transport = Transport::create([
            'brand_id' => $request->mark,
            'model_id' => $request->model,
            'category_id' => $request->category,
            'type_id' => $request->type,
            'weight' => $request->weight,
            'license_plate' => $request->license_plate ?? $request->license_plate_without_mask,
            'registration_country_id' => $request->registration_country,
            'manufacture_year' => $request->manufacture_year,
            'company_id' => $request->company,
            'driver_id' => $request->driver,
            'spending_empty' => $request->spending_empty,
            'spending_full' => $request->spending_full,
            'equipment_id' => $request->equipment
        ]);

        $transport->storeImage($request);
    }
}
