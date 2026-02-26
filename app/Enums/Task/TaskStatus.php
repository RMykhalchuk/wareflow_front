<?php

namespace App\Enums\Task;

enum TaskStatus: int
{
    case CREATED  = 1;
    case TO_DO    = 2;
    case DONE     = 3;
    case CANCELED = 4;
    case IN_PROGRESS = 5;

    public function label(): string
    {
        return match ($this) {
            self::CREATED => __('dictionaries.task_status.created'),
            self::TO_DO => __('dictionaries.task_status.to_do'),
            self::DONE => __('dictionaries.task_status.done'),
            self::CANCELED => __('dictionaries.task_status.canceled'),
            self::IN_PROGRESS => __('dictionaries.task_status.in_progress'),
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
