<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shifts')->insert([
            ['id' => 'de0aa71b-f9ad-4222-abc5-6196d7906078', 'shift' => 'DIA', 'end_time' => '14:00:00', 'shift_manager_id' => 1, 'created_at' => Carbon::now()->setTime(9, 0, 0), 'updated_at' => Carbon::now()->setTime(9, 0, 0)],
            ['id' => '46abeede-4221-47bb-9282-a54621ece52b', 'shift' => 'TARDE', 'end_time' => '22:00:00', 'shift_manager_id' => 1, 'created_at' => Carbon::now()->setTime(17, 0, 0), 'updated_at' => Carbon::now()->setTime(17, 0, 0)],
        ]);
    }
}
