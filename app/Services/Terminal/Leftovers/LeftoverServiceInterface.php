<?php

namespace App\Services\Terminal\Leftovers;


use App\Models\Entities\Goods\Goods;
use Illuminate\Support\Collection;


/**
 * LeftoverServiceInterface.
 */
interface LeftoverServiceInterface
{
    /**
     * @param string $query
     * @return Collection
     */
    public function findLeftoverByCell(string $query): Collection;

    /**
     * @param Goods $goods
     * @return array
     */
    public function getLeftoverByProduct(Goods $goods): array;

    /**
     * @param string $cellId
     * @return array
     */
    public function getLeftoverByCell(string $cellId): array;

    /**
     * @param string $cellId
     * @param Goods $goods
     * @return array
     */
    public function getCellContents(string $cellId, Goods $goods): array;

    /**
     * @param string $cellId
     * @return array
     */
    public function findLeftoversByCell(string $cellId): array;

    /**
     * @param string $cellId
     * @return array
     */
    public function findLoggedLeftoversByCell(string $cellId): array;

    /**
     * @param string $groupId
     * @return array
     */
    public function findLoggedLeftoversByGroup(string $groupId): array;
}
