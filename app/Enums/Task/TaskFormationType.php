<?php

namespace App\Enums\Task;

enum TaskFormationType: int
{
    case PLANNING  = 1;
    case DYNAMIC    = 2;

    public function label(): string
    {
        return match ($this) {
            self::PLANNING => __('dictionaries.task_formation_type.planning'),
            self::DYNAMIC => __('dictionaries.task_formation_type.dynamic'),
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


    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'name' => $this->name,
            'label' => $this->label()
        ];
    }
}
