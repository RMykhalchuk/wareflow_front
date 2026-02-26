<?php

namespace App\Enums\Task\Picking;

enum LeftoverLogType: string
{
    case MANUAL = 'manual';
    case SCAN   = 'scan';
    case PICK   = 'pick';

    public function label(): string
    {
        return match ($this) {
            self::MANUAL => __('dictionaries.task_status.manual'),
            self::SCAN => __('dictionaries.task_status.scan'),
            self::PICK => __('dictionaries.task_status.pick'),
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
