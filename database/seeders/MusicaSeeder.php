<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MusicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('musicas')->insert([
            ['nome' => 'Conta pro tio', 'duracao' => '04:00:00', 'genero_id' => '1', 'lancamento' => '2014-01-01'],
            ['nome' => 'Balaio de gato', 'duracao' => '03:05:00', 'genero_id' => '1', 'lancamento' => '2014-10-07'],
            ['nome' => 'Batendo água', 'duracao' => '15:09:00', 'genero_id' => '1', 'lancamento' => '2014-02-06'],
            ['nome' => 'Estoy aqui', 'duracao' => '05:00:00', 'genero_id' => '2', 'lancamento' => '2014-04-05'],
            ['nome' => 'Calma', 'duracao' => '20:15:00', 'genero_id' => '2', 'lancamento' => '2018-12-31'],
            ['nome' => 'A boa vista do peão de tropa', 'duracao' => '04:18:00', 'genero_id' => '1', 'lancamento' => '2014-12-31'],
            ['nome' => 'Espantando o Bagual', 'duracao' => '04:00:00', 'genero_id' => '1', 'lancamento' => '2014-12-31'],
            ['nome' => 'Cadela Baia', 'duracao' => '03:59:00', 'genero_id' => '1', 'lancamento' => '2014-12-31'],
            ['nome' => 'Sem paia e sem fumo', 'duracao' => '03:59:00', 'genero_id' => '1', 'lancamento' => '2014-12-31'],
            ['nome' => 'Despacito', 'duracao' => '04:00:00', 'genero_id' => '2', 'lancamento' => '2014-12-31'],
            ['nome' => 'Quando o verso vem pras casa', 'duracao' => '04:00:00', 'genero_id' => '1', 'lancamento' => '2014-12-31'],
            ['nome' => 'Perro Fiel', 'duracao' => '03:59:00', 'genero_id' => '2', 'lancamento' => '2014-12-31'],
            ['nome' => 'Bailando', 'duracao' => '04:00:00', 'genero_id' => '2', 'lancamento' => '2014-12-31'],
            ['nome' => 'El perdón', 'duracao' => '03:54:00', 'genero_id' => '2', 'lancamento' => '2014-12-31'],
            ['nome' => 'Súbeme la Radio', 'duracao' => '03:30:00', 'genero_id' => '2', 'lancamento' => '2014-12-31'],
            ['nome' => 'Sim ou Não', 'duracao' => '04:00:00', 'genero_id' => '2', 'lancamento' => '2014-12-31'],
            ['nome' => 'Felices los 4', 'duracao' => '03:59:00', 'genero_id' => '2', 'lancamento' => '2014-12-31'],
            ['nome' => 'Dança do Créu', 'duracao' => '01:59:00', 'genero_id' => '4', 'lancamento' => '2014-12-31'],
        ]);
    }
}
