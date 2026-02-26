<?php

namespace App\Models\Entities\WarehouseComponents;

use App\Models\Dictionaries\WarehouseType;
use App\Models\Entities\Location;
use App\Models\Entities\Schedule\Schedule;
use App\Models\Entities\Schedule\ScheduleException;
use App\Models\User;
use App\Traits\CompanySeparation;
use App\Traits\FilterByCompany;
use App\Traits\HasLocalId;
use App\Traits\HasUuid;
use App\Traits\Warehouse\WarehouseDelete;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Warehouse extends Model
{

    use SoftDeletes;
    use HasUuid;
    use HasLocalId;
    use CompanySeparation;
    use FilterByCompany;
    use WarehouseDelete;

    protected $keyType      = 'string';
    public    $incrementing = false;

    protected $guarded = [];

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<Schedule>
     */
    public function schedule(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Schedule::class, 'warehouse_id', 'id');
    }

    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasMany<ScheduleException>
     */
    public function conditions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ScheduleException::class, 'warehouse_id', 'id');
    }


    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<WarehouseType>
     */
    public function type(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(WarehouseType::class, 'id', 'type_id');
    }


    /**
     * @psalm-return \Illuminate\Database\Eloquent\Relations\HasOne<User>
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function warehouseErp()
    {
        return $this->hasOne(WarehouseErp::class, 'id', 'warehouses_erp_id');
    }


    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    public function zones()
    {
        return $this->hasMany(WarehouseZone::class)->orderBy('id');
    }

    public function erpWarehouses()
    {
        return $this->belongsToMany(
            WarehouseErp::class,
            'warehouse_erp_assignments',
            'warehouse_id',
            'warehouse_erp_id'
        );
    }

    public static function store(array $request): Warehouse
    {
        $warehouse = Warehouse::create(
            [
                'type_id' => $request['type_id'],
                'name' => $request['name'],
                'location_id' => $request['location_id'],
                'warehouse_erp_id' => (array_key_exists('warehouse_erp_id', $request) && $request['warehouse_erp_id'])
                    ? (is_array($request['warehouse_erp_id']) ? ($request['warehouse_erp_id'][0] ?? null) : $request['warehouse_erp_id'])
                    : null,
                'user_id' => \Auth::id()
            ]);

        if (array_key_exists('warehouse_erp_id', $request) && $request['warehouse_erp_id']) {
            $ids = is_array($request['warehouse_erp_id'])
                ? $request['warehouse_erp_id']
                : [$request['warehouse_erp_id']];
            $warehouse->erpWarehouses()->sync($ids);
        }

        if (array_key_exists('graphic', $request) && $request['graphic']) {
            $schedule = $request['graphic'];
            Schedule::store($schedule, warehouseId: $warehouse->id);

            if (array_key_exists('conditions', $request)) {
                $conditions = $request['conditions'];

                $warehouse->storeConditions($conditions);
            }
        }

        return $warehouse;
    }

    public function storeConditions($conditions): void
    {
        $conditionArray = [];

        foreach ($conditions as $condition) {
            $conditionArray[] = [
                'date_from' => $condition['date_from'],
                'date_to' => $condition['date_to'] ?? null,
                'type_id' => $condition['type_id'],
                'warehouse_id' => $this->id,
                'work_from' => $condition['work_from'] ?? null,
                'work_to' => $condition['work_to'] ?? null,
                'break_from' => $condition['break_from'] ?? null,
                'break_to' => $condition['break_to'] ?? null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ];
        }

        ScheduleException::insert($conditionArray);
    }

    public function updateConditions($conditions): void
    {
        if ($conditions) {
            $conditionArray = [];
            ScheduleException::where('warehouse_id', $this->id)->delete();
            foreach ($conditions as $condition) {
                $conditionArray[] = [
                    'date_from' => $condition['date_from'],
                    'date_to' => $condition['date_to'] ?? null,
                    'type_id' => $condition['type_id'],
                    'warehouse_id' => $this->id,
                    'work_from' => $condition['work_from'] ?? null,
                    'work_to' => $condition['work_to'] ?? null,
                    'break_from' => $condition['break_from'] ?? null,
                    'break_to' => $condition['break_to'] ?? null,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ];
            }
            ScheduleException::insert($conditionArray);
        }
    }

    /**
     * @return void
     */
    #[\Override]
    /**
     * @return void
     */
    public function delete()
    {
        Schedule::where('warehouse_id', $this->id)->delete();
        ScheduleException::where('warehouse_id', $this->id)->delete();

        parent::delete();
    }


    public function updateData(array $data): Warehouse
    {
        $this->update(
            [
                'type_id' => $data['type_id'],
                'name' => $data['name'],
                'location_id' => $data['location_id'],
                // Тимчасова сумісність зі старою колонкою
                'warehouse_erp_id' => (array_key_exists('warehouse_erp_id', $data) && $data['warehouse_erp_id'])
                    ? (is_array($data['warehouse_erp_id']) ? ($data['warehouse_erp_id'][0] ?? null) : $data['warehouse_erp_id'])
                    : null,
            ]);

        // Оновлення зв'язків у півод-таблиці
        if (array_key_exists('warehouse_erp_id', $data)) {
            $ids = $data['warehouse_erp_id'] ?: [];
            $ids = is_array($ids) ? $ids : [$ids];
            $this->erpWarehouses()->sync($ids);
        }

        return $this;
    }

}
