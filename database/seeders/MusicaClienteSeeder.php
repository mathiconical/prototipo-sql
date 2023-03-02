<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MusicaClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('musicas_has_clientes')->insert([
            ['musica_id' => '4', 'cliente_id' => '1', 'data' => '2019-10-21 12:19:00'],
            ['musica_id' => '1', 'cliente_id' => '1', 'data' => '2019-10-21 18:28:00'],
            ['musica_id' => '1', 'cliente_id' => '1', 'data' => '2019-10-21 18:29:00'],
        ]);
    }
}
