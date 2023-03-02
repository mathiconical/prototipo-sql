<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('question_images')->insert([
            [
                'question_id' => 1,
                'image_path' => 'clientes.png'
            ],
            [
                'question_id' => 2,
                'image_path' => 'clientes.png, planos.png'
            ],
            [
                'question_id' => 3,
                'image_path' => 'artistas.png'
            ],
            [
                'question_id' => 4,
                'image_path' => 'planos.png'
            ],
            [
                'question_id' => 5,
                'image_path' => 'artistas.png, gravadoras.png'
            ],
            [
                'question_id' => 6,
                'image_path' => 'artistas.png, gravadoras.png'
            ],
            [
                'question_id' => 7,
                'image_path' => 'musicas.png, artistas.png, musica_has_artistas.png'
            ],
            [
                'question_id' => 8,
                'image_path' => 'generos.png, musicas.png'
            ],
            [
                'question_id' => 9,
                'image_path' => 'clientes.png, musicas.png, musica_has_clientes.png'
            ],
            [
                'question_id' => 10,
                'image_path' => 'clientes.png, planos.png'
            ],
        ]);
    }
}
