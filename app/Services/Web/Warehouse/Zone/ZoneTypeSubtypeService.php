<?php

namespace App\Services\Web\Warehouse\Zone;

use App\Models\Entities\WarehouseComponents\Zone\ZoneSubtype;
use App\Models\Entities\WarehouseComponents\Zone\ZoneType;
use App\Services\Web\Warehouse\Zone\ZoneTypeSubtypeServiceInterface;

/**
 * ZoneTypeSubtypeService.
 */
class ZoneTypeSubtypeService implements ZoneTypeSubtypeServiceInterface
{

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return ZoneType::query()
            ->orderBy('name')
            ->get()
            ->map(static function (ZoneType $type): array {
                return [
                    'id'              => $type->id,
                    'name'            => $type->translated_name,
                    'key'             => $type->name,
                ];
            })
            ->all();
    }

    /**
     * @param int $typeId
     * @return array
     */
    public function getSubtypeByTypeId(int $typeId): array
    {
        if ($typeId <= 0) {
            return [];
        }


        return ZoneSubtype::query()
            ->whereHas('types', static function ($q) use ($typeId) {
                $q->where('zone_type_id', $typeId);
            })
            ->orderBy('name')
            ->get()
            ->map(static function (ZoneSubtype $subtype): array {
                return [
                    'id'              => $subtype->id,
                    'name'            => $subtype->translated_name,
                    'key'             => $subtype->name,
                ];
            })
            ->all();
    }
}
