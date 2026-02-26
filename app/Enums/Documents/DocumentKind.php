<?php

namespace App\Enums\Documents;

enum DocumentKind : string {
    case ARRIVAL = 'arrival'; //прихідний
    case OUTCOME   = 'outcome'; // розхідний
    case INNER   = 'inner';
    case NEUTRAL = 'neutral';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public function label(): array
    {
        return match ($this) {
            self::ARRIVAL => __('dictionaries.document_kind.arrival'),
            self::OUTCOME => __('dictionaries.document_kind.outcome'),
            self::INNER   => __('dictionaries.document_kind.inner'),
            self::NEUTRAL => __('dictionaries.document_kind.neutral'),
        };
    }


    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'name' => $this->name,
            'label' => $this->label()
        ];
    }

    public static function all(): array
    {
        return array_map(
            fn (self $case) => $case->toArray(),
            self::cases()
        );
    }
}
