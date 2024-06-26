<?php

namespace Database\Seeders;

use App\Enums\UserRolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministrativesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('administratives')->insert([
            ['name' => 'Administrador', 'user_id' => UserRolesEnum::ADMINISTRATIVE->value],
        ]);
    }
}
