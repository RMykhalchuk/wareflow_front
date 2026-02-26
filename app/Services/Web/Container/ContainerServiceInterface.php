<?php

namespace App\Services\Web\Container;

/**
 * ContainerServiceInterface.
 */
interface ContainerServiceInterface
{
    /**
     * @param string $cellId
     * @return array
     */
    public function getWithLeftoversByCell(string $cellId): array;
}
