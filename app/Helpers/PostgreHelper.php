<?php

namespace App\Helpers;

class PostgreHelper
{
    public static function dbConcat(...$fields): string
    {
        return implode(" || ' ' || ", $fields);
    }
}
