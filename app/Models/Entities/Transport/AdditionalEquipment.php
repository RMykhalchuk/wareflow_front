<?php

namespace App\Models\Entities\Transport;

use App\Interfaces\StoreImageInterface;
use App\Models\Dictionaries\AdditionalEquipmentBrand;
use App\Models\Dictionaries\AdditionalEquipmentModel;
use App\Models\Dictionaries\Adr;
use App\Models\Dictionaries\TransportDownload;
use App\Models\Dictionaries\TransportType;
use App\Models\Entities\Address\Country;
use App\Models\Entities\Company\Company;
use App\Traits\AdditionalEquipmentDataTrait;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class AdditionalEquipment extends Model
{
    use SoftDeletes;
    use AdditionalEquipmentDataTrait;
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

    public function downloadMethods()
    {
        $ids = json_decode($this->download_methods);

        return TransportDownload::whereIn('id', $ids)->pluck('name', 'id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<AdditionalEquipmentBrand>
     */
    public function brand(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AdditionalEquipmentBrand::class, 'id', 'brand_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<AdditionalEquipmentModel>
     */
    public function model(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AdditionalEquipmentModel::class, 'id', 'model_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Adr>
     */
    public function adr(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Adr::class, 'id', 'adr_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<TransportType>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TransportType::class, 'id', 'type_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Transport>
     */
    public function transport(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transport::class, 'id', 'transport_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<Country>
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\BelongsTo<Company>
     */
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function storeImage(\App\Http\Requests\Web\Transport\EquipmentRequest $request): void
    {
        if ($request->file('image')) {
            $imageService = resolve(StoreImageInterface::class);
            $imageService->setImage($request, $this, 'transport-equipment');
        }
    }

    public static function store(\App\Http\Requests\Web\Transport\EquipmentRequest $request): void
    {
        $equipment = AdditionalEquipment::create([
            'brand_id' => $request->mark,
            'model_id' => $request->model,
            'type_id' => $request->type,
            'license_plate' => $request->license_plate ?? $request->license_plate_without_mask,
            'country_id' => $request->registration_country,
            'manufacture_year' => $request->manufacture_year,
            'company_id' => $request->company,
            'transport_id' => $request->transport,
            'download_methods' => $request->download_methods,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'volume' => $request->volume,
            'capacity_eu' => $request->capacity_eu,
            'capacity_am' => $request->capacity_am,
            'adr_id' => $request->adr,
            'carrying_capacity' => $request->carrying_capacity,
            'hydroboard' => $request->hydroboard == 'true',
        ]);

        $equipment->storeImage($request);
    }

    public function edit(\App\Http\Requests\Web\Transport\EquipmentRequest $request): void
    {
        $this->update([
            'brand_id' => $request->mark,
            'model_id' => $request->model,
            'type_id' => $request->type,
            'license_plate' => $request->license_plate ?? $request->license_plate_without_mask,
            'country_id' => $request->registration_country,
            'manufacture_year' => $request->manufacture_year,
            'company_id' => $request->company,
            'transport_id' => $request->transport,
            'download_methods' => $request->download_methods,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'volume' => $request->volume,
            'capacity_eu' => $request->capacity_eu,
            'capacity_am' => $request->capacity_am,
            'adr_id' => $request->adr,
            'carrying_capacity' => $request->carrying_capacity,
            'hydroboard' => $request->hydroboard == 'true'
        ]);
        $this->storeImage($request);
    }
}
