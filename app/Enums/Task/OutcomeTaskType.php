<?php

namespace App\Enums\Task;

enum OutcomeTaskType: string
{
    case PICKING = 'picking';
    case CONTROL = 'control';
    case OUTCOME = 'outcome';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
