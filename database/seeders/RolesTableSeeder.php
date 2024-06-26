<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\UserRolesEnum;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => UserRolesEnum::ADMIN->value, 'name' => UserRolesEnum::roles[0], 'description' => 'Rol de administrador'],
            ['id' => UserRolesEnum::ADMINISTRATIVE->value, 'name' => UserRolesEnum::roles[1], 'description' => 'Rol de administrativo'],
            ['id' => UserRolesEnum::SHIFTMANAGER->value, 'name' => UserRolesEnum::roles[2], 'description' => 'Rol de jefe de turno'],
            ['id' => UserRolesEnum::OPERATOR->value, 'name' => UserRolesEnum::roles[3], 'description' => 'Rol de operador'],
            ['id' => UserRolesEnum::LINE->value, 'name' => UserRolesEnum::roles[4], 'description' => 'Rol de línea'],
        ]);
    }
}
