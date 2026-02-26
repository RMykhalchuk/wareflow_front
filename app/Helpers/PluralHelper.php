<?php

namespace App\Helpers;

final class PluralHelper
{
    /**
     * Повертає правильну форму слова залежно від числа та мови
     *
     * @param int $number
     * @param array $forms   // ['one', 'few', 'many'] для укр або ['singular','plural'] для англ
     * @param string|null $locale
     * @return string
     */
    public static function word(int $number, array $forms, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'uk') {
            $n = abs($number) % 100;
            $n1 = $n % 10;

            if ($n > 10 && $n < 20) {
                return $forms[2] ?? ''; // many
            }
            if ($n1 === 1) {
                return $forms[0] ?? ''; // one
            }
            if ($n1 >= 2 && $n1 <= 4) {
                return $forms[1] ?? ''; // few
            }
            return $forms[2] ?? ''; // many
        }

        // англійська
        return $number === 1
            ? ($forms[0] ?? '')
            : ($forms[1] ?? '');
    }
}
