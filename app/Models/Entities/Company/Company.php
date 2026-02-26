<?php

namespace App\Models\Entities\Company;

use App\Enums\Currency;
use App\Http\Requests\Web\Company\LegalCompanyRequest;
use App\Http\Requests\Web\Company\PhysicalCompanyRequest;
use App\Interfaces\StoreFileInterface;
use App\Models\Dictionaries\CompanyType;
use App\Models\Entities\Address\AddressDetails;
use App\Models\Entities\System\Workspace;
use App\Models\Entities\User\UserWorkingData;
use App\Models\User;
use App\Services\Web\Company\CompanyContextService;
use App\Traits\Company\CompanyDeleteTrait;
use App\Traits\CompanyDataTrait;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Entities\Company\LegalCompany;

/**
 * Company.
 */
final class Company extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CompanyDataTrait;
    use HasUuid;
    use CompanyDeleteTrait;

    protected $keyType      = 'string';
    public    $incrementing = false;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var \class-string[]
     */
    protected $casts = [
        'currency' => Currency::class,
    ];

    /**
     * @return void
     */
    #[\Override]
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($company) {
            $file = resolve(StoreFileInterface::class);
            $file->deleteFile('company/image', $company, 'img_type');
            UserWorkingData::where('company_id', $company->id)->delete();

            if ($company->company_type_id == 1) {
                PhysicalCompany::find($company->company_id)->delete();
            } else {
                $companyType = LegalCompany::find($company->company_id);
                $file->deleteFile('company/docs/install', $companyType, 'install_doctype');
                $file->deleteFile('company/docs/registration', $companyType, 'reg_doctype');
                $companyType->delete();
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function company()
    {
        return $this->morphTo();
    }

    /**
     * @psalm-return HasOne<AddressDetails>
     */
    public function address(): HasOne
    {
        return $this->hasOne(AddressDetails::class, 'id', 'address_id');
    }

    /**
     * @psalm-return HasOne<CompanyType>
     */
    public function type(): HasOne
    {
        return $this->hasOne(CompanyType::class, 'id', 'company_type_id');
    }

    /**
     * @psalm-return HasOne<Workspace>
     */
    public function workspace(): HasOne
    {
        return $this->hasOne(Workspace::class, 'id', 'workspace_id');
    }

    /**
     * @psalm-return BelongsToMany<Workspace>
     */
    public function companiesInWorkspace(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'company_to_workspaces');
    }

    /**
     * @psalm-return HasMany<CompanyRequest>
     */
    public function requests(): HasMany
    {
        return $this->hasMany(CompanyRequest::class, 'company_id', 'id');
    }

    /**
     * @psalm-return HasManyThrough<User>
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            UserWorkingData::class,
            'company_id',
            'id',
            'id',
            'user_id'
        );
    }

    /**
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        // Замість relationLoaded використовуємо пряму перевірку
        if (!isset($this->company)) {
            return ' - ';
        }

        return $this->company_type_id == 1
            ? $this->company->surname
            . ' ' . mb_substr($this->company->first_name, 0, 1) . '.'
            . mb_substr($this->company->patronymic, 0, 1)
            : $this->company->name;
    }

    /**
     * @param PhysicalCompanyRequest $request
     * @return Company
     */
    public static function createPhysical(PhysicalCompanyRequest $request): Company
    {
        if (!$request->filled('onboarding_create')) {
            CompanyContextService::apply();
        }

        $file = resolve(StoreFileInterface::class);

        $address = AddressDetails::create(
            [
                'country_id' => $request->country,
                'settlement_id' => $request->city,
                'street_id' => $request->street,
                'building_number' => $request->building_number,
                'apartment_number' => $request->flat,
                'gln' => $request->gln,
            ]
        );

        $physicalCompany = PhysicalCompany::create(
            [
                'first_name' => $request->firstName,
                'surname' => $request->lastName,
                'patronymic' => $request->patronymic
            ]);

        $company = parent::create(
            [
                'email' => $request->email,
                'company_type' => 'App\Models\Entities\Company\PhysicalCompany',
                'company_id' => $physicalCompany->id,
                'company_type_id' => (CompanyType::where('key', 'physical')->first('id'))->id,
                'ipn' => $request->ipn,
                'address_id' => $address->id,
                'bank' => $request->bank,
                'iban' => $request->iban,
                'mfo' => $request->mfo,
                'about' => $request->about,
                'currency' => $request->currency,
                'creator_id' => $request->has_creator == 'true' ? Auth::id() : null,
                "creator_company_id" => $request->filled('onboarding_create') ? null : User::currentCompany(),
                'category_id' => $request->category
            ]);

        if ($request->filled('onboarding_create')) {
            self::setUserCompany($company['id']);
            $company->update(['creator_company_id' => $company->id]);
        }

        if ($request->has_creator == 'true') {
            $userWorkingData = UserWorkingData::create(
                [
                    'user_id' => Auth::id(),
                    'company_id' => $company->id,
                    'workspace_id' => !empty(Workspace::current()) ? Workspace::current() : null,
                    'creator_company_id' => $company->id
                ]);

            $userWorkingData->assignRole('admin');
        }


        if (property_exists($request, 'has_creator') || !empty(Workspace::current())) {
            CompanyToWorkspace::create(
                [
                    'company_id' => $company->id,
                    'workspace_id' => Workspace::current()
                ]);
        }


        $file->setFile($request->file('image'), 'company/image', $company, 'img_type');

        return $company;
    }

    /**
     * @param LegalCompanyRequest $request
     * @param false $fromOnboarding
     * @return Company
     */
    public static function createLegal(LegalCompanyRequest $request, false $fromOnboarding = false): Company
    {
        if (!$request->filled('onboarding_create')) {
            CompanyContextService::apply();
        }

        $file = resolve(StoreFileInterface::class);

        $address = AddressDetails::create(
            [
                'country_id' => $request->country,
                'settlement_id' => $request->city,
                'street_id' => $request->street,
                'building_number' => $request->building_number,
                'apartment_number' => $request->flat,
                'gln' => $request->gln,
            ]
        );

        $legalAddress = AddressDetails::create(
            [
                'country_id' => $request->u_country,
                'settlement_id' => $request->u_city,
                'street_id' => $request->u_street,
                'building_number' => $request->u_building_number,
                'apartment_number' => $request->u_flat,
                'gln' => $request->u_gln,
            ]
        );

        $legalCompany = LegalCompany::create(
            [
                'name' => $request->company_name,
                'edrpou' => $request->edrpou,
                'legal_type_id' => $request->legal_entity,
                'legal_address_id' => $legalAddress->id,
            ]);

        $company = parent::create(
            [
                'email' => $request->email,
                'company_type' => LegalCompany::class,
                'company_id' => $legalCompany->id,
                'company_type_id' => (CompanyType::where('key', 'legal')->first('id'))->id,
                'ipn' => $request->ipn,
                'address_id' => $address->id,
                'bank' => $request->bank,
                'iban' => $request->iban,
                'mfo' => $request->mfo,
                'about' => $request->about,
                'currency' => $request->currency,
                'creator_id' => $request->has_creator == 'true' ? Auth::id() : null,
                "creator_company_id" => $request->filled('onboarding_create') ? null : User::currentCompany(),
                'category_id' => $request->company_category
            ]);

        if ($request->filled('onboarding_create')) {
            self::setUserCompany($company['id']);
            $company->update(['creator_company_id' => $company->id]);
        }


        if ($request->has_creator == 'true') {
            $userWorkingData = UserWorkingData::create(
                [
                    'user_id' => Auth::id(),
                    'company_id' => $company->id,
                    'workspace_id' => !empty(Workspace::current()) ? Workspace::current() : null,
                    'creator_company_id' => $company->id
                ]);

            $userWorkingData->assignRole('admin');
        }

        if (property_exists($request, 'has_creator') || !empty(Workspace::current())) {
            CompanyToWorkspace::create([
                                           'company_id' => $company->id,
                                           'workspace_id' => Workspace::current()
                                       ]);
        }

        $file->setFile($request->file('image'), 'company/image', $company, 'img_type');

        if ($request->file('registration_doc') && $request->file('ust_doc')) {
            $file->setFile(
                $request->file('registration_doc'),
                'company/docs/registration',
                $legalCompany,
                'reg_doctype'
            );
            $file->setFile(
                $request->file('ust_doc'),
                'company/docs/install',
                $legalCompany,
                'install_doctype'
            );

            $legalCompany->update([
                                      'reg_docname' => $request->file('registration_doc')->getClientOriginalName(),
                                      'install_docname' => $request->file('ust_doc')->getClientOriginalName(),
                                  ]);
        }

        return $company;
    }

    /**
     * @param $companyId
     * @return void
     */
    private static function setUserCompany($companyId)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Кешуємо значення, якщо воно ще не встановлене
            if (!isset($user->cached_company_id)) {
                $user->cached_company_id = $companyId;
            }
        }
    }

    /**
     * @param PhysicalCompanyRequest $request
     * @return void
     */
    public function updatePhysical(PhysicalCompanyRequest $request): void
    {
        $company = Company::filterByWorkspace()->where(['company_id' => $this->company_id])->first();

        if (!$company) {
            return;
        }

        AddressDetails::find($this->address_id)->update(
            [
                'country_id' => $request->country,
                'settlement_id' => $request->city,
                'street_id' => $request->street,
                'building_number' => $request->building_number,
                'apartment_number' => $request->flat,
                'gln' => $request->gln,
            ]
        );

        PhysicalCompany::find($this->company_id)->update(
            [
                'first_name' => $request->firstName,
                'surname' => $request->lastName,
                'patronymic' => $request->patronymic
            ]);

        $this->update(
            [
                'email' => $request->email,
                'ipn' => $request->ipn,
                'bank' => $request->bank,
                'iban' => $request->iban,
                'mfo' => $request->mfo,
                'about' => $request->about,
                'currency' => $request->currency,
                'category_id' => $request->category
            ]);

        if ($request->file('image')) {
            $file = resolve(StoreFileInterface::class);
            $file->setFile($request->file('image'), 'company/image', $this, 'img_type');
        }
    }

    /**
     * @param LegalCompanyRequest $request
     * @return void
     */
    public function updateLegal(LegalCompanyRequest $request): void
    {
        $company = Company::filterByWorkspace()->where(['company_id' => $this->company_id])->first();

        if (!$company) {
            return;
        }

        $file = resolve(StoreFileInterface::class);
        $legalCompany = LegalCompany::find($this->company_id);
        $legalCompany->update([
                                  'name' => $request->company_name,
                                  'edrpou' => $request->edrpou,
                              ]);

        AddressDetails::find($this->address_id)->update(
            [
                'country_id' => $request->country,
                'settlement_id' => $request->city,
                'street_id' => $request->street,
                'building_number' => $request->building_number,
                'apartment_number' => $request->flat,
                'gln' => $request->gln,
            ]
        );

        AddressDetails::find($legalCompany->legal_address_id)->update(
            [
                'country_id' => $request->u_country,
                'settlement_id' => $request->u_city,
                'street_id' => $request->u_street,
                'building_number' => $request->u_building_number,
                'apartment_number' => $request->u_flat,
                'gln' => $request->u_gln,
            ]
        );

        $this->update(
            [
                'email' => $request->email,
                'ipn' => $request->ipn,
                'bank' => $request->bank,
                'iban' => $request->iban,
                'mfo' => $request->mfo,
                'about' => $request->about,
                'category_id' => $request->company_category,
                'currency' => $request->currency
            ]);

        if ($request->file('registration_doc')) {
            $file->setFile(
                $request->file('registration_doc'),
                'company/docs/registration',
                $legalCompany,
                'reg_doctype'
            );

            $legalCompany->update([
                                      'reg_docname' => $request->file('registration_doc')->getClientOriginalName(),
                                  ]);
        }

        if ($request->file('ust_doc')) {
            $file->setFile(
                $request->file('ust_doc'),
                'company/docs/install',
                $legalCompany,
                'install_doctype'
            );

            $legalCompany->update([
                                      'install_docname' => $request->file('ust_doc')->getClientOriginalName(),
                                  ]);
        }
    }
}
