<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('generos')->insert(
            [
                [
                    'descricao' => 'Gaúcha',
                ],
                [
                    'descricao' => 'Pop',
                ],
                [
                    'descricao' => 'Rock',
                ],
                [
                    'descricao' => 'Funk',
                ],
            ]
        );
    }
}
