<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('areas')->insert([
            ['name' => 'Produccion'],
            ['name' => 'Mantenimiento'],
            ['name' => 'Ventas'],
        ]);
    }
}
