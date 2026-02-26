<?php

namespace App\Services\Web\Warehouse\Sector;

use App\Models\Entities\WarehouseComponents\Sector;
use App\Models\Entities\WarehouseComponents\WarehouseZone;
use Illuminate\Support\Facades\DB;

class SectorService implements SectorServiceInterface
{
    public function create(WarehouseZone $zone, array $data): Sector
    {
        return DB::transaction(function () use ($zone, $data) {

            // Фільтр тільки однобуквенних кодів
            $letterFilter = "code ~ '^[A-Z]$'";

            // 1. Отримуємо останній сектор з блокуванням
            $lastSector = DB::table('sectors')
                ->where('zone_id', $zone->id)
                ->whereRaw($letterFilter)
                ->orderBy('code', 'desc')
                ->limit(1)
                ->lockForUpdate()
                ->first();

            if ($lastSector && $lastSector->code) {

                if ($lastSector->code === 'Z') {
                    throw new \Exception('Maximum number of sectors (26) reached');
                }

                $nextCode = chr(ord($lastSector->code) + 1);

            } else {
                $nextCode = 'A';
            }

            $data['code'] = $nextCode;
            $sector = $zone->sectors()->create($data);

            $lastCell = $sector->cells()
                ->where('code', 'like', $sector->code . '-%')
                ->orderBy('code', 'desc')
                ->first();

            if ($lastCell) {
                $prefixLength = strlen($sector->code . '-');
                $number = (int)substr($lastCell->code, $prefixLength);
                $number++;
            } else {
                $number = 1;
            }

            $code = $sector->code . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);

            $sector->cells()->create(
                [
                    'code' => $code,
                    'parent_type' => 'sector',
                ]);

            return $sector;
        });
    }


    public function update(WarehouseZone $zone, Sector $sector, array $data): Sector
    {
        if ($sector->zone_id !== $zone->id) {
            abort(404, 'Sector not found in this zone');
        }

        $sector->update($data);
        return $sector;
    }

    public function delete(WarehouseZone $zone, Sector $sector): void
    {
        if ($sector->zone_id !== $zone->id) {
            abort(404, 'Sector not found in this zone');
        }

        $sector->delete();
    }
}
