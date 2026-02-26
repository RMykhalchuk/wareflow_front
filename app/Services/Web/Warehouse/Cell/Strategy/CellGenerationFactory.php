<?php

namespace App\Services\Web\Warehouse\Cell\Strategy;

class CellGenerationFactory
{
    public static function make(string $type): CellGenerationInterface
    {
        return match ($type) {
            'linear' => new LinearGeneration(),
            default => throw new \InvalidArgumentException("Invalid generation type"),
        };
    }
}
