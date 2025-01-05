<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classes')->insert([
            [
                'name' => 'Comércio',
                'related_color' => '#ed675f',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 13:04:20',
            ],
            [
                'name' => 'Serviço',
                'related_color' => '#f5ac6c',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 13:04:40',
            ],
            [
                'name' => 'Religião',
                'related_color' => '#841c8a',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 13:06:00',
            ],
            [
                'name' => 'Indústria',
                'related_color' => '#525252',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 13:06:36',
            ],
            [
                'name' => 'Indústria e comércio',
                'related_color' => '#8f411d',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 15:09:48',
            ],
            [
                'name' => 'Esporte/desporto',
                'related_color' => '#6aab4f',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 15:06:45',
            ],
            [
                'name' => 'Atividade educacional',
                'related_color' => '#4d53a1',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 15:07:47',
            ],
            [
                'name' => 'Atividade social, cultural e política',
                'related_color' => '#ffbace',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-10-19 20:40:58',
            ],
            [
                'name' => 'Imobiliário',
                'related_color' => '#a3a3a3',
                'created_at' => '2021-09-23 12:04:35',
                'updated_at' => '2021-09-23 15:08:45',
            ],
            [
                'name' => 'Recuperação e tratamento de resíduos',
                'related_color' => '#3c9f85',
                'created_at' => '2021-09-23 12:04:36',
                'updated_at' => '2021-09-23 15:09:05',
            ],
            [
                'name' => 'Comércio e serviço',
                'related_color' => '#a16028',
                'created_at' => '2021-09-23 12:04:36',
                'updated_at' => '2021-09-23 15:10:01',
            ],
            [
                'name' => 'Saúde',
                'related_color' => '#090909',
                'created_at' => '2021-09-23 12:04:36',
                'updated_at' => '2021-10-08 23:57:29',
            ],
            [
                'name' => 'Transporte',
                'related_color' => '#8f411d',
                'created_at' => '2021-09-23 12:04:36',
                'updated_at' => '2021-09-23 15:10:49',
            ],
            [
                'name' => 'Agricultura',
                'related_color' => '#5fd35f',
                'created_at' => '2021-09-23 18:43:31',
                'updated_at' => '2021-10-19 17:48:44',
            ],
        ]);
    }
}
