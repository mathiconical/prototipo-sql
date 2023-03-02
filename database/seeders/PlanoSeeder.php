<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('planos')->insert(
            [
                [
                    'descricao' => 'Light',
                    'valor' => 29.99,
                    'limite' => 100,
                ],
                [
                    'descricao' => 'Sem nome',
                    'valor' => 39.99,
                    'limite' => 500,
                ],
                [
                    'descricao' => 'Full',
                    'valor' => 49.99,
                    'limite' => 999999,
                ],
            ]
        );
    }
}
