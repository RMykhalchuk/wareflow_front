<?php

namespace App\Services\Web\Warehouse;

use App\Http\Requests\Web\Warehouse\WarehouseErpRequest;
use App\Http\Requests\Web\Warehouse\WarehouseRequest;
use App\Http\Requests\Web\Warehouse\WarehouseScheduleRequest;
use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseErp;

interface WarehouseServiceInterface
{
    public function getCreateFormData(): array;

    public function storeWarehouse(WarehouseRequest $request): Warehouse;

    public function getEditFormData(Warehouse $warehouse): array;

    public function updateWarehouse(WarehouseRequest $request, Warehouse $warehouse): Warehouse;

    public function updateSchedule(WarehouseScheduleRequest $request, Warehouse $warehouse): void;

    public function destroyWarehouse(Warehouse $warehouse): void;

    public function getRevisionData(Warehouse $warehouse): array;

    /**
     * @param string|null $creatorCompanyId
     * @param string|null $companyId
     * @return array
     */
    public function listOptions(?string $creatorCompanyId = null, ?string $companyId = null): array;

    public function storeWarehouseErp(WarehouseErpRequest $request): WarehouseErp;

    public function updateWarehouseErp(WarehouseErpRequest $request, WarehouseErp $warehouseErp): WarehouseErp;
}

