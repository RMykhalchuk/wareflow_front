<?php

namespace App\Services\Web\Warehouse;

use App\Http\Requests\Web\Warehouse\WarehouseErpRequest;
use App\Http\Requests\Web\Warehouse\WarehouseRequest;
use App\Http\Requests\Web\Warehouse\WarehouseScheduleRequest;
use App\Services\Web\Company\CompanyDictionaryService;
use App\Models\{Dictionaries\ExceptionType,
    Dictionaries\WarehouseType,
    Entities\Location,
    Entities\Schedule\Schedule,
    Entities\Schedule\SchedulePattern,
    Entities\WarehouseComponents\Warehouse,
    Entities\WarehouseComponents\WarehouseErp};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseService implements WarehouseServiceInterface
{
    public function getCreateFormData(): array
    {
        return [
            'patterns' => SchedulePattern::where('type', 'warehouse')->get(),
            'types' => WarehouseType::all(),
            'exceptions' => ExceptionType::all(),
            'warehouse_erp' => WarehouseErp::all()
        ];
    }

    public function storeWarehouse(WarehouseRequest $request): Warehouse
    {
        return Warehouse::store($request->validated());
    }

    public function getEditFormData(Warehouse $warehouse): array
    {
        return [
            'warehouse' => $warehouse->load(['conditions.type', 'erpWarehouses']),
            'patterns' => SchedulePattern::where('type', 'warehouse')
                ->where('creator_company_id', Auth::user()->workingData->creator_company_id)->get(),
            'types' => WarehouseType::all(),
            'exceptions' => ExceptionType::all(),
            'schedule' => $warehouse->schedule
        ];
    }

    public function updateWarehouse(WarehouseRequest $request, Warehouse $warehouse): Warehouse
    {
        return $warehouse->updateData($request->validated());
    }

    public function updateSchedule(WarehouseScheduleRequest $request, Warehouse $warehouse): void
    {
        $schedule = $request->graphic ?? [];
        $conditions = $request->conditions;

        $warehouse->updateConditions($conditions);
        Schedule::where('warehouse_id', $warehouse->id)->delete();
        Schedule::store($schedule, warehouseId: $warehouse->id);
    }

    public function destroyWarehouse(Warehouse $warehouse): void
    {
        $warehouse->delete();
    }

    public function getRevisionData(Warehouse $warehouse): array
    {
        return [
            'warehouse' => $warehouse->load(['conditions.type']),
            'exceptions' => ExceptionType::all(),
        ];
    }

    /**
     * @param string|null $creatorCompanyId
     * @param string|null $companyId
     * @return array
     */
    public function listOptions(?string $creatorCompanyId = null, ?string $companyId = null): array
    {
        \DB::enableQueryLog();

        $locationsSub = Location::withoutGlobalScopes()
            ->select(['id', 'company_id']);

        $q = Warehouse::withoutGlobalScopes()
            ->whereNull('deleted_at')
            ->joinSub($locationsSub, 'cl', fn($join) => $join->on('cl.id', '=', 'warehouses.location_id'))
            ->select([
                         'warehouses.id',
                         'warehouses.name',
                         'warehouses.location_id',
                         'cl.company_id',
                     ])
            ->orderBy('warehouses.name');

        if ($companyId) {
            $q->where('cl.company_id', $companyId);
        } else {
            $companyIds = new CompanyDictionaryService()
                ->getDictionaryList();

            $companyIds = collect($companyIds)->pluck('id')->filter()->values()->all();

            if (empty($companyIds)) {
                $result = [];

                return $result;
            }
            $q->whereIn('cl.company_id', $companyIds);
        }

        $rows = $q->get();

        $result = $rows->map(fn($r)
            => [
            'id' => $r->id,
            'name' => $r->name,
            'location_id' => $r->location_id,
            'company_id' => $r->company_id,
        ])->toArray();

        return $result;
    }

    public function storeWarehouseErp(WarehouseErpRequest $request): WarehouseErp
    {
        return WarehouseErp::create($request->validated());
    }

    public function updateWarehouseErp(WarehouseErpRequest $request, WarehouseErp $warehouseErp): WarehouseErp
    {
        return $warehouseErp->updateData($request->validated());
    }
}
