<?php

namespace App\Enums\Goods;

enum GoodsStatus : int {

    case ACTIVE = 1;
    case BLOCKED   = 2;

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Активний',
            self::BLOCKED => 'Заблокований',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::ACTIVE => 'active',
            self::BLOCKED => 'reserved',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => 'Товар доступний...',
            self::BLOCKED => 'Товар заблокований',
        };
    }

    // Отримати всі значення
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    // Отримати всі імена
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    // Для використання в формах (select options)
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }

    // Для використання в формах з англійськими лейблами
    public static function optionsEn(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->labelEn()])
            ->toArray();
    }

    // Перевірка чи можна змінити статус
    public function canChangeTo(self $newStatus): bool
    {
        return match ([$this, $newStatus]) {
            [self::ACTIVE, self::BLOCKED] => true,
            [self::BLOCKED, self::ACTIVE] => true,
            default => false,
        };
    }

    // Чи доступний контейнер для бронювання
    public function isAvailable(): bool
    {
        return $this === self::ACTIVE;
    }

    // Чи зарезервований контейнер
    public function isReserved(): bool
    {
        return $this === self::BLOCKED;
    }

    // Для API відповідей
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'name' => $this->name,
            'label' => $this->label(),
            'label_en' => $this->labelEn(),
            'description' => $this->description(),
            'is_available' => $this->isAvailable(),
            'is_reserved' => $this->isReserved(),
        ];
    }
}
