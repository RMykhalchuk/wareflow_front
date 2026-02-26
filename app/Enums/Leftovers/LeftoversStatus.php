<?php

namespace App\Enums\Leftovers;

/**
 * Leftovers statuses enums.
 */
enum LeftoversStatus: int
{
    case ACTIVE  = 1;

    case RESERVED = 2;

    case BLOCKED = 3;

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Доступно',
            self::RESERVED => 'Зарезервовано',
            self::BLOCKED => 'Заблоковано',
        };
    }

    /**
     * @return string
     */
    public function labelEn(): string
    {
        return match ($this) {
            self::ACTIVE => 'Available',
            self::RESERVED => 'Reserved',
            self::BLOCKED => 'Blocked',
        };
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => 'Товар доступний...',
            self::RESERVED => 'Товар зарезервований',
            self::BLOCKED => 'Товар заблокований',
        };
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * @return array
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }

    /**
     * @return array
     */
    public static function optionsEn(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->labelEn()])
            ->toArray();
    }

    /**
     * @param LeftoversStatus $newStatus
     * @return bool
     */
    public function canChangeTo(self $newStatus): bool
    {
        return match ([$this, $newStatus]) {
            [self::ACTIVE, self::BLOCKED] => true,
            [self::BLOCKED, self::ACTIVE] => true,
            default => false,
        };
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * @return bool
     */
    public function isReserved(): bool
    {
        return $this === self::RESERVED;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this === self::BLOCKED;
    }

    /**
     * @return array
     */
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
            'is_blocked' => $this->isBlocked(),
        ];
    }
}
