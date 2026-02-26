<?php

namespace App\Enums;

/**
 * Roles.
 */
enum Roles: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';


    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            static::SUPER_ADMIN => __('localization.user_role_system_administrator'),
            static::ADMIN => __('localization.user_role_administrator'),
            static::USER => __('localization.user_role_user'),
        };
    }
}
