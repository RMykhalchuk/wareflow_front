<?php


namespace App\Models;

use App\Http\Requests\Web\User\UserRequest;
use App\Interfaces\AvatarInterface;
use App\Models\Dictionaries\Position;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Schedule\Schedule;
use App\Models\Entities\Schedule\ScheduleException;
use App\Models\Entities\System\Workspace;
use App\Models\Entities\User\UserWorkingData;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Scopes\UserCompanyScope;
use App\Services\Web\Auth\AuthContextService;
use App\Services\Web\Auth\GuardContext;
use App\Traits\HasUuid;
use App\Traits\UserDataTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * User.
 */
final class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use UserDataTrait;
    use HasUuid;


    // use HasLocalIdCustomWorkspace;

    // private string $workspaceColumn = 'current_workspace_id';
    protected         $keyType      = 'string';
    public            $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d',
        'last_seen' => 'datetime:d-m-Y',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'current_warehouse_id',
    ];


    /**
     * @param $query
     * @return mixed
     */
    public function scopeSameCompany($query): mixed
    {
        return $query->whereHas(
            'workingData',
            fn($q) => $q->where('creator_company_id', app(AuthContextService::class)->getCompanyId())
        );
    }


    public function getInitials(): string
    {
        $surname = $this->surname ?? '';
        $name = $this->name ? mb_substr($this->name, 0, 1) . '.' : '';
        $patronymic = $this->patronymic ? mb_substr($this->patronymic, 0, 1) . '.' : '';

        return trim($surname . ' ' . $name . $patronymic);
    }

    /**
     * @return string
     */
    public static function currentCompany(): string
    {
        return Auth::user()->workingData->company_id;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeWithoutCompanyFilter($query): mixed
    {
        return $query->withoutGlobalScope(UserCompanyScope::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @return bool|null
     */

    #[\Override]
    public function delete(): ?bool
    {
        Schedule::where('user_id', $this->id)->delete();
        ScheduleException::where('user_id', $this->id)->delete();
        $this->removeAvatar();

        if ($this->position->name == 'driver') {
            $this->deleteDriver();
        }

        return parent::delete();
    }

    /**
     * @return HasOne
     */
    public function workingData(): HasOne
    {
        return $this->hasOne(UserWorkingData::class, 'user_id', 'id')
            ->where('workspace_id', Workspace::current());
    }

    /**
     * @return HasOne
     */
    public function allWorkingData(): HasOne
    {
        return $this->hasOne(UserWorkingData::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function workingDataByGuard(): mixed
    {
        return $this->hasOne(UserWorkingData::class, 'user_id', 'id')
            ->where('workspace_id', Workspace::current())
            ->withoutCreatorCompany();
    }

    /**
     * @psalm-return BelongsToMany<Workspace>
     */
    public function usersInWorkspace(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'user_working_data', 'user_id');
    }

    /**
     * @psalm-return HasMany<Company>
     */
    public function createdCompanies(): HasMany
    {
        return $this->hasMany(Company::class, 'creator_id');
    }

    /**
     * @psalm-return HasMany<Schedule>
     */
    public function schedule(): HasMany
    {
        return $this->hasMany(Schedule::class, 'user_id', 'id');
    }

    /**
     * @param UserRequest $request
     * @return void
     */
    public function updateData(UserRequest $request): void
    {
        $payload = $request->only(['surname', 'name', 'patronymic', 'birthday', 'phone', 'email', 'sex']);

        if ($request->has('pin')) {
            $payload['pin'] = $request->input('pin');
        }

        $this->update($payload);

        $this->setAvatar($request);
        $workingData = $this->workingData()->first();
        if (!$workingData->hasRole('super_admin')) {
            $workingData->syncRoles([$request->role]);
        }

        $workingData->position_id = (Position::where('key', $request->position)->first(['id']))->id;


        if ($request->position === 'driver') {
            if ($request->need_file) {
                $workingData->saveDriver($request, $this);
            } else {
                $workingData->health_book_number    = $request->health_book_number;
                $workingData->driving_license_number = $request->driving_license_number;
                $workingData->driver_license_date   = $request->driver_license_date;
                $workingData->health_book_date      = $request->health_book_date;
            }
        }

        $workingData->save();
        $workingData->refresh();

        if ($request->has('warehouse_ids')) {
            $ids = array_values(array_filter(
                (array) $request->input('warehouse_ids'),
                fn ($v) => $v !== null && $v !== ''
            ));
            $workingData->warehouses()->sync($ids);
        }
    }

    /**
     * @psalm-return HasOne<Warehouse>
     */
    public function warehouse(): HasOne
    {
        return $this->hasOne(Warehouse::class, 'user_id', 'id');
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * @param $value
     * @return void
     */
    public function setPinAttribute($value): void
    {
        $pin = is_null($value) ? null : (string)$value;

        $this->attributes['pin'] = $pin;

        if ($pin === null || $pin === '') {
            $this->attributes['pin_hash'] = null;
            $this->attributes['pin_attempts'] = 0;
            $this->attributes['pin_locked_until'] = null;
        } else {
            $this->attributes['pin_hash'] = Hash::make($pin);
            $this->attributes['pin_attempts'] = 0;
            $this->attributes['pin_locked_until'] = null;
        }
    }

    /**
     * @return string|null
     */
    public function getPinHash(): ?string
    {
        return $this->getAttribute('pin');
    }

    /**
     * @psalm-return BelongsToMany<Workspace>
     */
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(
            Workspace::class,
            'user_working_data',
            'user_id',
            'workspace_id'
        );
    }

    /**
     * @psalm-return HasOne<Workspace>
     */
    public function current_workspace(): HasOne
    {
        return $this->hasOne(Workspace::class, 'id', 'current_workspace_id');
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        return Cache::has('is_online' . $this->id);
    }

    /**
     * @param UserRequest $request
     * @return void
     */
    public function setAvatar(UserRequest $request): void
    {
        $avatar = resolve(AvatarInterface::class);

        if ($request->avatar) {
            $avatar->setAvatar($request, $this);
        }
    }

    /**
     * @return void
     */
    public function removeAvatar(): void
    {
        $avatar = resolve(AvatarInterface::class);

        if ($this->avatar_type) {
            $avatar->deleteAvatarIfExist($this);
        }
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        if (!$this->avatar_type) {
            return asset('assets/icons/entity/user/avatar_empty.png');
        }

        return asset(
            '/files/uploads/user/avatars/' . $this->id . '.' . $this->avatar_type
        );
    }

    /**
     * @param $array
     * @return void
     */
    public function updateOnboarding($array): void
    {
        $array['new_user'] = 0;
        $this->update($array);
    }

    /**
     * @psalm-return HasMany<ScheduleException>
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(ScheduleException::class, 'user_id', 'id');
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return !is_null($this->role) && in_array($this->role->key, ['super_admin', 'admin']);
    }

    /**
     * @return string|null
     */
    public function initial(): ?string
    {
        $surname = trim((string)($this->surname ?? ''));
        $name = trim((string)($this->name ?? ''));

        if ($surname === '' && $name === '') {
            return null;
        }

        $nameInitial = $name !== ''
            ? mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . '.'
            : '';

        return trim($surname . ' ' . $nameInitial);
    }

    /**
     * @return string|null
     */
    public function getCurrentWarehouseIdAttribute(): ?string
    {
        $guard = app(GuardContext::class)->getGuard();

        $wd = $this->workingDataByGuard;

        if (! $wd) {
            return null;
        }

        return $guard === 'terminal'
            ? $wd->current_warehouse_app_id
            : $wd->current_warehouse_id;
    }

    /**
     * @return Warehouse|null
     */
    public function currentWarehouse(): ?Warehouse
    {
        $wd = $this->workingData;

        if (! $wd) {
            return null;
        }

        $wd->loadMissing('currentWarehouse');

        return $wd->currentWarehouse;
    }

    /**
     * @return Warehouse|null
     */
    public function currentWarehouseApp(): ?Warehouse
    {
        $wd = $this->workingData;

        if (! $wd) {
            return null;
        }

        $wd->loadMissing('currentWarehouseApp');

        return $wd->currentWarehouse;
    }

    /**
     * @param string|null $warehouseId
     * @param bool $enforceAllowed
     * @return array
     */
    public function setCurrentWarehouse(?string $warehouseId, bool $enforceAllowed = false): array
    {
        $wd = $this->workingDataByGuard;

        if (!$wd) {
            return [
                'success'                => false,
                'current_warehouse_id'   => null,
                'current_warehouse_name' => null,
            ];
        }

        if ($enforceAllowed && $warehouseId) {
            if (!$wd->warehouses()->whereKey($warehouseId)->exists()) {
                return [
                    'success'                => false,
                    'current_warehouse_id'   => $wd->current_warehouse_id,
                    'current_warehouse_name' => $wd->current_warehouse_id
                        ? Warehouse::query()->whereKey($wd->current_warehouse_id)->value('name')
                        : null,
                ];
            }
        }

        $wd->current_warehouse_id = $warehouseId;
        $ok = $wd->save();

        return [
            'success'                => (bool)$ok,
            'current_warehouse_id'   => $warehouseId,
            'current_warehouse_name' => $warehouseId
                ? Warehouse::query()->whereKey($warehouseId)->value('name')
                : null,
        ];
    }

    /**
     * @param string|null $warehouseId
     * @param bool $enforceAllowed
     * @return array
     */
    public function setCurrentWarehouseApp(?string $warehouseId, bool $enforceAllowed = false): array
    {
        $wd = $this->workingDataByGuard;

        if (!$wd) {
            return [
                'success'                => false,
                'current_warehouse_id'   => null,
                'current_warehouse_name' => null,
            ];
        }

        if ($enforceAllowed && $warehouseId) {
            if (!$wd->warehouses()->whereKey($warehouseId)->exists()) {
                return [
                    'success'                => false,
                    'current_warehouse_id'   => $wd->current_warehouse_app_id,
                    'current_warehouse_name' => $wd->current_warehouse_app_id
                        ? \DB::table('warehouses')->where('id', $warehouseId)?->first()?->name
                        : null,
                ];
            }
        }

        $wd->current_warehouse_app_id = $warehouseId;
        $ok = $wd->save();

        return [
            'success'                => (bool)$ok,
            'current_warehouse_id'   => $warehouseId,
            'current_warehouse_name' => $warehouseId
                ? \DB::table('warehouses')->where('id', $warehouseId)?->first()?->name
                : null,
        ];
    }
}
