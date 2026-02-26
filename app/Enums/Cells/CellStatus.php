<?php

namespace App\Enums\Cells;

/**
 * CellStatus.
 */
enum CellStatus: int
{
    case ACTIVE  = 1;
    case BLOCKED = 2;

    /**
     * Labels.
     */
    public const LABELS = [
        self::ACTIVE->value  => 'Доступно',
        self::BLOCKED->value => 'Заблоковано',
    ];

    /**
     * @return string
     */
    public function label(): string
    {
        return self::LABELS[$this->value] ?? (string) $this->value;
    }

    /**
     * @return string[]
     */
    public static function options(): array
    {
        return [
            self::ACTIVE->value  => self::LABELS[self::ACTIVE->value],
            self::BLOCKED->value => self::LABELS[self::BLOCKED->value],
        ];
    }
}
