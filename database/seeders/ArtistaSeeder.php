<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('artistas')->insert([
            ['nome' => 'Mano Lima', 'gravadora_id' => 2],
            ['nome' => 'Shakira', 'gravadora_id' => 4],
            ['nome' => 'Luiz Marenco', 'gravadora_id' => 5],
            ['nome' => 'Pedro Capó', 'gravadora_id' => 4],
            ['nome' => 'Farruko', 'gravadora_id' => 4],
            ['nome' => 'Alicia Keys', 'gravadora_id' => 4],
            ['nome' => 'Joca Martins', 'gravadora_id' => 2],
            ['nome' => 'José Cláudio Machado', 'gravadora_id' => 2],
            ['nome' => 'Luis Fonsi', 'gravadora_id' => 4],
            ['nome' => 'Nicky Jam', 'gravadora_id' => 4],
            ['nome' => 'Enrique Iglesias', 'gravadora_id' => 4],
            ['nome' => 'Gente de Zona', 'gravadora_id' => 4],
            ['nome' => 'Descemer Bueno', 'gravadora_id' => 4],
            ['nome' => 'Zion', 'gravadora_id' => 4],
            ['nome' => 'Lennox', 'gravadora_id' => 4],
            ['nome' => 'Maluma', 'gravadora_id' => 4],
            ['nome' => 'Anitta', 'gravadora_id' => 4],
            ['nome' => 'Mettallica', 'gravadora_id' => 4],
            ['nome' => 'MC Créu', 'gravadora_id' => 1],
        ]);
    }
}
