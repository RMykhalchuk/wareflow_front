<?php

namespace App\Enums\Documents;

enum IncomeDocumentFields : string {
    case SUPPLIER = "1select_field_1";
    case ALLOCATION = "2select_field_2";
    case COMMENT = "3text_field_3";

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}
