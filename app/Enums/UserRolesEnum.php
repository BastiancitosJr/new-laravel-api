<?php

namespace App\Enums;

enum UserRolesEnum: int
{
    case ADMIN = 1; // This is an admin user
    case ADMINISTRATIVE = 2; // This is an administrative user
    case SHIFTMANAGER = 3; // This is a shift manager
    case OPERATOR = 4; // This is an operator
    case LINE =  5; // This is a line

    public const roles = [
        'Administrador del sistema',
        'Administrativo',
        'Jefe de turno',
        'Operador',
        'Linea',
    ];

    /**
     * Get the role id from the role name.
     *
     * @param string $roleName
     * @return string|null
     */
    public static function stringToRoleId(string $roleName): ?int

    {
        return match ($roleName) {
            UserRolesEnum::roles[0] => self::ADMIN->value,
            UserRolesEnum::roles[1] => self::ADMINISTRATIVE->value,
            UserRolesEnum::roles[2] => self::SHIFTMANAGER->value,
            UserRolesEnum::roles[3] => self::OPERATOR->value,
            UserRolesEnum::roles[4] => self::LINE->value,
            default => null,
        };
    }

    /**
     * Get the role name from the role id.
     *
     * @param int $roleId
     * @return string|null
     */
    public static function idToRoleString(int $roleId): ?string

    {
        return match ($roleId) {
            self::ADMIN->value => UserRolesEnum::roles[0],
            self::ADMINISTRATIVE->value => UserRolesEnum::roles[1],
            self::SHIFTMANAGER->value => UserRolesEnum::roles[2],
            self::OPERATOR->value => UserRolesEnum::roles[3],
            self::LINE->value => UserRolesEnum::roles[4],
            default => null,
        };
    }

    /**
     * Get all roles names.
     *
     * @return array
     */

    public static function allRolesNames(): array
    {
        return self::roles;
    }
}
