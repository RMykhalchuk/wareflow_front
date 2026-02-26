<?php

namespace App\Services\Web\Warehouse\Zone;

use App\Models\Entities\WarehouseComponents\Warehouse;
use App\Models\Entities\WarehouseComponents\WarehouseZone;

class ZoneService implements ZoneServiceInterface
{
    public function create(Warehouse $warehouse, array $data): WarehouseZone
    {
        $zone = $warehouse->zones()->create($data);

        $lastCell = $zone->cells()
            ->where('code', 'like', $zone->name . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastCell) {
            // витягаємо числову частину з коду і збільшуємо
            $number = (int)substr($lastCell->code, strlen($zone->name+1));
            $number++;
        } else {
            $number = 1;
        }

        // форматуємо код: zoneName + 6 цифр з нулями спереду
        $code = $zone->name . '-' .str_pad($number, 6, '0', STR_PAD_LEFT);

        // створюємо нову клітинку з кодом
        $zone->cells()->create(
            [
                'code' => $code,
                'parent_type' => 'zone',
            ]);

        return $zone;
    }


    public function update(Warehouse $warehouse, WarehouseZone $zone, array $data): WarehouseZone
    {
        if ($zone->warehouse_id !== $warehouse->id) {
            abort(404, 'Zone not found in this warehouse');
        }

        $zone->update($data);
        return $zone;
    }

    public function delete(Warehouse $warehouse, WarehouseZone $zone): void
    {
        if ($zone->warehouse_id !== $warehouse->id) {
            abort(404, 'Zone not found in this warehouse');
        }

        $zone->delete();
    }
}

