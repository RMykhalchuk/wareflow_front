<?php

namespace App\Services\Web\Warehouse\Zone;

/**
 * ZoneTypeSubtypeServiceInterface.
 */
interface ZoneTypeSubtypeServiceInterface
{
    /**
     * @return array
     */
    public function getTypes(): array;

    /**
     * @param int $typeId
     * @return array
     */
    public function getSubtypeByTypeId(int $typeId): array;
}
