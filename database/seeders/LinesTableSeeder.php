<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lines')->insert([
            ['name' => 'Linea de envasado 1', 'area_id' => 1, 'user_id' => UserRolesEnum::LINE->value],
        ]);
    }
}
