<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftManagersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shift_managers')->insert([
            ['name' => 'Jefe de turno 1', 'user_id' => UserRolesEnum::SHIFTMANAGER->value, 'area_id' => 2],
        ]);
    }
}
