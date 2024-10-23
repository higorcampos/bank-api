<?php

namespace App\Enums;

class UserRoleEnums
{
    public const ADMINISTRATOR = 'ADMINISTRATOR';
    public const GUEST = 'GUEST';
    public const SUPPORT = 'SUPPORT';

    public static function getAllRoles(): array
    {
        return [
            self::ADMINISTRATOR,
            self::GUEST,
            self::SUPPORT,
        ];
    }
}