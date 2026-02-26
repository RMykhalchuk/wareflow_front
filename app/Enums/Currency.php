<?php

namespace App\Enums;

enum Currency: int
{
    case UAH = 1;
    case USD = 2;
    case EUR = 3;

    public function label(): string
    {
        return match ($this) {
            static::UAH => 'UAH',
            static::USD => 'USD',
            static::EUR => 'EUR',
        };
    }
}

