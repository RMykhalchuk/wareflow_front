<?php

namespace App\Services\Web\Warehouse\Cell\Strategy;

class CellGeneratorStrategy
{
    private CellGenerationInterface $strategy;

    public function __construct(CellGenerationInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function generate(array $params): array
    {
        return $this->strategy->generate($params);
    }
}
