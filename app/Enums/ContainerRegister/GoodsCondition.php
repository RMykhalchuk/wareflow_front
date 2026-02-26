<?php

namespace App\Enums\ContainerRegister;

enum GoodsCondition : int {

    case OK = 1;
    case FIGHT = 2;

    public function label(): string
    {
        return match ($this) {
            self::OK => 'Ок',
            self::FIGHT => 'Бій',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::OK => 'Ok',
            self::FIGHT => 'Fight',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public static function optionsEn(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($case) => [$case->value => $case->labelEn()])
            ->toArray();
    }

    public function canChangeTo(self $newStatus): bool
    {
        return match ([$this, $newStatus]) {
            [self::OK, self::FIGHT] => true,
            [self::FIGHT, self::OK] => true,
            default => false,
        };
    }


    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'name' => $this->name,
            'label' => $this->label(),
            'label_en' => $this->labelEn(),
        ];
    }
}

