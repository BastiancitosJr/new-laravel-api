<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KpiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('productivities')->insert([
            ['packed_tons' => 2000, 'tons_produced' => 8200, 'line_id' => 1, 'shift_id' => 'de0aa71b-f9ad-4222-abc5-6196d7906078', 'created_at' => now(), 'updated_at' => now()],
            ['packed_tons' => 3000, 'tons_produced' => 7200, 'line_id' => 1, 'shift_id' => '46abeede-4221-47bb-9282-a54621ece52b', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('cleanlinesses')->insert([
            ['done' => true, 'comment' => 'Limpieza 1 comment', 'line_id' => 1, 'shift_id' => 'de0aa71b-f9ad-4222-abc5-6196d7906078', 'created_at' => now(), 'updated_at' => now()],
            ['done' => true, 'comment' => 'Limpieza 2 comment', 'line_id' => 1, 'shift_id' => '46abeede-4221-47bb-9282-a54621ece52b', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('securities')->insert([
            ['result' => 4, 'comment' => 'Seguridad 1 comment', 'line_id' => 1, 'shift_id' => 'de0aa71b-f9ad-4222-abc5-6196d7906078', 'created_at' => now(), 'updated_at' => now()],
            ['result' => 3, 'comment' => 'Seguridad 2 comment', 'line_id' => 1, 'shift_id' => '46abeede-4221-47bb-9282-a54621ece52b', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('peer_observations')->insert([
            ['result' => 6, 'comment' => 'Observacion por pares 1 comment', 'line_id' => 1, 'shift_id' => 'de0aa71b-f9ad-4222-abc5-6196d7906078', 'created_at' => now(), 'updated_at' => now()],
            ['result' => 5, 'comment' => 'Observacion por pares 2 comment', 'line_id' => 1, 'shift_id' => '46abeede-4221-47bb-9282-a54621ece52b', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('labeling_qualities')->insert([
            ['deviations' => 1, 'audits' => 1, 'comment' => 'Calidad de etiquetado 1 comment', 'line_id' => 1, 'shift_id' => 'de0aa71b-f9ad-4222-abc5-6196d7906078', 'created_at' => now(), 'updated_at' => now()],
            ['deviations' => 4, 'audits' => 2, 'comment' => 'Calidad de etiquetado 2 comment', 'line_id' => 1, 'shift_id' => '46abeede-4221-47bb-9282-a54621ece52b', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('monthly_programming_progress')->insert([
            ['monthly_order' => 2390, 'line_id' => 1, 'shift_id' => 'de0aa71b-f9ad-4222-abc5-6196d7906078', 'created_at' => now(), 'updated_at' => now()],
            ['monthly_order' => 2390, 'line_id' => 1, 'shift_id' => '46abeede-4221-47bb-9282-a54621ece52b', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}