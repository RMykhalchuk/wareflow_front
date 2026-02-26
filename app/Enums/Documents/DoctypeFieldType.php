<?php

namespace App\Enums\Documents;

enum DoctypeFieldType: string
{
    case Text = 'text';
    case Range = 'range';
    case Select = 'select';
    case Label = 'label';
    case Date = 'date';
    case DateRange = 'dateRange';
    case DateTimeRange = 'dateTimeRange';
    case TimeRange = 'timeRange';
    case Switch = 'switch';
    case UploadFile = 'uploadFile';
    case Comment = 'comment';
    case DateTime = 'dateTime';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

