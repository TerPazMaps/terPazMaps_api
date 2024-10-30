<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StreetConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insere os dados na tabela street_conditions
        DB::table('street_conditions')->insert([
            [
                'id' => 1,
                'condition' => 'Rua pavimentada (asfalto)',
                'color' => '#ffad29',
                'created_at' => '2024-03-09 20:10:27',
                'updated_at' => '2024-03-28 17:06:50',
            ],
            [
                'id' => 2,
                'condition' => 'Rua não pavimentada',
                'color' => '#EFE944',
                'created_at' => '2024-03-09 20:10:41',
                'updated_at' => '2024-03-28 21:47:43',
            ],
            [
                'id' => 3,
                'condition' => 'Trecho com alagamento ou inundação',
                'color' => '#005FFF',
                'created_at' => '2024-03-09 20:10:50',
                'updated_at' => '2024-03-28 21:47:15',
            ],
            [
                'id' => 4,
                'condition' => 'Trecho precisando de reparos',
                'color' => '#F54516',
                'created_at' => '2024-03-09 20:10:58',
                'updated_at' => '2024-03-28 21:46:56',
            ],
            [
                'id' => 5,
                'condition' => 'Trecho obstruído (vegetação ou entulho)',
                'color' => '#000000',
                'created_at' => '2024-03-09 20:11:10',
                'updated_at' => '2024-03-28 21:43:50',
            ],
            [
                'id' => 6,
                'condition' => 'Rua pavimentada (bloquete)',
                'color' => '#7C7C7C',
                'created_at' => '2024-03-09 20:11:17',
                'updated_at' => '2024-03-28 21:47:58',
            ],
            [
                'id' => 7,
                'condition' => 'Sem tratamento',
                'color' => '#fcd1c4',
                'created_at' => '2024-03-09 20:11:26',
                'updated_at' => '2024-03-28 17:09:39',
            ],
        ]);
    }
}
