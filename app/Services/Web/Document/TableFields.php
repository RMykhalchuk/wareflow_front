<?php

namespace App\Services\Web\Document;

final class TableFields
{
    /**
     * @psalm-param 'header' $block
     */
    public static function format($formatedArray, string $block)
    {
        foreach (json_decode($formatedArray['data'], true)[$block] as $key => $value) {
            $formatedArray['data->' . $block . '->' . $key] = TableFields::getFormattedField($key, $value);
        }
        return $formatedArray;
    }

    public static function getFormattedField($key, $value)
    {
        if (stripos($key, 'uploadFile_') && $value != null) {
            $str = '';
            foreach ($value as $key => $element) {
                $str .= $key == count($value) - 1 ? $element : $element . '; ';
            }
            return $str;
        } elseif ((stripos($key, 'range_') || stripos($key, 'timeRange_') || stripos($key, 'dateRange_')) && $value != null) {
            return ($value[0] ?? '') . ' - ' . ($value[1] ?? '');
        } elseif (stripos($key, 'dateTimeRange_') && $value != null) {
            return $value[0] . ' ' . $value[1] . '-' . $value[2];
        } elseif (stripos($key, 'dateTime_') && $value != null) {
            return $value[0] . ' ' . $value[1];
        } else {
            return $value;
        }
    }
}
