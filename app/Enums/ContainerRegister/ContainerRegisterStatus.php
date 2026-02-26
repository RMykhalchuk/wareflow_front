<?php

namespace App\Enums\ContainerRegister;

use Illuminate\Support\Facades\Lang;

enum ContainerRegisterStatus : int {

    case EMPTY = 1;
    case WITH_PRODUCT   = 2;
    case DEACTIVATED   = 3;

    public function label(): string
    {
        return match ($this) {
            self::EMPTY => __('dictionaries.container_status.empty'),
            self::WITH_PRODUCT => __('dictionaries.container_status.with_product'),
            self::DEACTIVATED => __('dictionaries.container_status.deactivated'),
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    // Для API відповідей
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
        ];
    }

    public static function all(): array
    {
        return array_map(fn($case) => $case->toArray(), self::cases());
    }

    public function canBeDeactivated(): bool
    {
        return $this === self::EMPTY;
    }
}
