<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operators')->insert([
            ['name' => 'Operador 1', 'rut' => '11111111-1', 'line_id' => 1, 'area_id' => 1, 'user_id' => UserRolesEnum::OPERATOR->value],
            ['name' => 'Operador 2', 'rut' => '20544764-4', 'line_id' => 1, 'area_id' => 2, 'user_id' => null],
        ]);
    }
}
