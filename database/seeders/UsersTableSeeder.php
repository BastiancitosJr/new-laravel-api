<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'app_admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => UserRolesEnum::ADMIN->value, // Admin
            ],
            [
                'username' => 'administrative_user@example.com',
                'password' => Hash::make('password'),
                'role_id' => UserRolesEnum::ADMINISTRATIVE->value, // Administrative
            ],
            [
                'username' => 'shiftmanager_user@example.com',
                'password' => Hash::make('password'),
                'role_id' => UserRolesEnum::SHIFTMANAGER->value, // ShiftManager
            ],
            [
                'username' => 'operator_user@example.com',
                'password' => Hash::make('password'),
                'role_id' => UserRolesEnum::OPERATOR->value, // Operator
            ],
            [
                'username' => 'line_user@example.com',
                'password' => Hash::make('password'),
                'role_id' => UserRolesEnum::LINE->value, // Line
            ],
        ]);
    }
}
