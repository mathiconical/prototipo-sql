<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MusicaArtistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('musicas_has_artistas')->insert([
            ['musica_id' => 1, 'artista_id' => 1],
            ['musica_id' => 2, 'artista_id' => 1],
            ['musica_id' => 3, 'artista_id' => 3],
            ['musica_id' => 4, 'artista_id' => 2],
            ['musica_id' => 5, 'artista_id' => 4],
            ['musica_id' => 5, 'artista_id' => 5],
            ['musica_id' => 5, 'artista_id' => 6],
            ['musica_id' => 6, 'artista_id' => 8],
            ['musica_id' => 6, 'artista_id' => 7],
            ['musica_id' => 6, 'artista_id' => 3],
            ['musica_id' => 7, 'artista_id' => 1],
            ['musica_id' => 8, 'artista_id' => 1],
            ['musica_id' => 9, 'artista_id' => 1],
            ['musica_id' => 10, 'artista_id' => 9],
            ['musica_id' => 11, 'artista_id' => 3],
            ['musica_id' => 12, 'artista_id' => 2],
            ['musica_id' => 12, 'artista_id' => 10],
            ['musica_id' => 13, 'artista_id' => 13],
            ['musica_id' => 13, 'artista_id' => 11],
            ['musica_id' => 13, 'artista_id' => 12],
            ['musica_id' => 14, 'artista_id' => 11],
            ['musica_id' => 14, 'artista_id' => 10],
            ['musica_id' => 16, 'artista_id' => 17],
            ['musica_id' => 16, 'artista_id' => 16],
            ['musica_id' => 15, 'artista_id' => 11],
            ['musica_id' => 15, 'artista_id' => 15],
            ['musica_id' => 15, 'artista_id' => 14],
            ['musica_id' => 15, 'artista_id' => 13],
            ['musica_id' => 17, 'artista_id' => 16],
            ['musica_id' => 18, 'artista_id' => 19],
        ]);
    }
}
