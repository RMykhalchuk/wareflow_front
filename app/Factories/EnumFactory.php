<?php

namespace App\Factories;

use App\Enums\ContainerRegister\ContainerRegisterStatus;

class EnumFactory
{
    public static function container_register_status()
    {
        return ContainerRegisterStatus::all();
    }
}
