<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('clientes')->insert([
            ['login' => 'Sandro', 'senha' => bcrypt('5andr0'), 'plano_id' => '1', 'email' => 'sandrocamargo@unipampa.edu.br'],
            ['login' => 'Papa', 'senha' => bcrypt('v5t1c5n0'), 'plano_id' => '3', 'email' => 'papa@vaticano.com'],
            ['login' => 'Neymar', 'senha' => bcrypt('caicai'), 'plano_id' => '3', 'email' => 'bateu-caiu@selecao.com'],
        ]);
    }
}
