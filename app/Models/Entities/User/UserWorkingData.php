<?php

namespace App\Models\Entities\User;

use App\Http\Requests\Web\User\UserRequest;
use App\Interfaces\StoreFileInterface;
use App\Models\Dictionaries\Position;
use App\Models\Entities\Company\Company;
use App\Models\Entities\Schedule\Schedule;
use App\Models\Entities\Schedule\ScheduleException;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\User;
use App\Traits\CompanySeparation;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * UserWorkingData.
 */
final class UserWorkingData extends Model
{
    use SoftDeletes;
    use HasRoles;
    use HasUuid;
    use CompanySeparation;
    use HasLocalId;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $guard_name = 'web';

    protected $table = 'user_working_data';

    protected $with = [
        'roles',
    ];

    /**
     * @psalm-return HasOne<Company>
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    /**
     * @psalm-return HasMany<Schedule>
     */
    public function schedule(): HasMany
    {
        return $this->hasMany(Schedule::class, 'user_id', 'id');
    }

    /**
     * @psalm-return HasOne<User>
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * @psalm-return BelongsToMany<Role>
     */
    public function role(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'model_has_roles',
            'model_id',
            'role_id'
        );
    }

    /**
     * @psalm-return HasOne<Position>
     */
    public function position(): HasOne
    {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    /**
     * @psalm-return HasMany<ScheduleException>
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(ScheduleException::class, 'user_id', 'id');
    }

    /**
     * @return BelongsToMany
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(
            Warehouse::class,
            'user_working_data_warehouse',
            'user_working_data_id',
            'warehouse_id'
        )->withTimestamps();
    }

    /**
     * @return BelongsTo
     */
    public function currentWarehouse(): BelongsTo
    {
        return $this->belongsTo(
            Warehouse::class,
            'current_warehouse_id',
            'id'
        );
    }

    /**
     * @return BelongsTo
     */
    public function currentWarehouseApp(): BelongsTo
    {
        return $this->belongsTo(
            Warehouse::class,
            'current_warehouse_app_id',
            'id'
        );
    }

    /**
     * @param $user_id
     * @return void
     */
    public function deleteDriver($user_id): void
    {
        $file = resolve(StoreFileInterface::class);
        $file->deleteFile('driver/driving_license', $this, 'driving_license_doctype', $user_id);
        $file->deleteFile('driver/health_book', $this, 'health_book_doctype', $user_id);
    }

    /**
     * @param array $schedule
     * @return void
     */
    public function updateSchedule(array $schedule): void
    {
        Schedule::where('user_id', $this->id)->delete();
        Schedule::store($schedule, $this->id);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return !is_null($this->role) && in_array($this->role->key, ['super_admin', 'admin']);
    }

    /**
     * @param UserRequest $request
     * @return void
     */
    public function saveDriver(UserRequest$request): void
    {
        $file = resolve(StoreFileInterface::class);
        $file->setFile(
            $request->file('driving_license'),
            'driver/driving_license',
            $this,
            'driving_license_doctype'
        );
        $file->setFile(
            $request->file('health_book'),
            'driver/health_book',
            $this,
            'health_book_doctype'
        );
        $this->update([
            'driving_license_number' => $request->driving_license_number,
            'health_book_number' => $request->health_book_number,
            'driver_license_date' => $request->driver_license_date,
            'health_book_date' => $request->health_book_date
        ]);
    }
}
