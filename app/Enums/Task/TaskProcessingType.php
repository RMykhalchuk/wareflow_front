<?php

namespace App\Enums\Task;

enum TaskProcessingType: string
{
    case DEFAULT = "default";
    case PICKING = "picking";

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => __('dictionaries.task_type.default'),
            self::PICKING => __('dictionaries.task_type.picking'),
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
