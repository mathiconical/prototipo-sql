<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GravadoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('gravadoras')->insert([
            ['nome' => 'Artista Independente', 'valor_contrato' => 0, 'vencimento_contrato' => '2020-12-31'],
            ['nome' => 'ACIT', 'valor_contrato' => 50000, 'vencimento_contrato' => '2020-12-31'],
            ['nome' => 'Som Livre', 'valor_contrato' => 100000, 'vencimento_contrato' => '2020-12-31'],
            ['nome' => 'Sony Music', 'valor_contrato' => 500000, 'vencimento_contrato' => '2024-12-31'],
            ['nome' => 'USA Discos', 'valor_contrato' => 10000, 'vencimento_contrato' => '2020-12-31'],
        ]);
    }
}
