<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('questions')->insert([
            [
                'pergunta' => 'Quantos clientes estão cadastrados?',
                'resposta' => "SELECT COUNT(id) AS total FROM clientes;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Quantos clientes estão cadastrados em cada plano?',
                'resposta' => "SELECT descricao, COUNT(clientes.id) AS total FROM clientes JOIN planos ON planos.id = clientes.plano_id GROUP BY plano_id;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Quais os artistas que estão no sistema?',
                'resposta' => "SELECT nome FROM artistas;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Quais são os planos, valores e limites de downloads?',
                'resposta' => "SELECT descricao, valor, limite FROM planos ORDER BY valor;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Quantos artistas tem cada gravadora?',
                'resposta' => "SELECT gravadoras.nome, COUNT(artistas.id) AS total FROM artistas JOIN gravadoras ON artistas.gravadora_id = gravadoras.id GROUP BY gravadoras.id;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Qual gravadora tem mais artistas?',
                'resposta' => "SELECT gravadoras.nome, COUNT(artistas.id) AS total FROM artistas JOIN gravadoras ON artistas.gravadora_id = gravadoras.id GROUP BY gravadoras.id ORDER BY COUNT(artistas.id) DESC LIMIT 0, 1;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Quais são as músicas do artista “Mano Lima”?',
                'resposta' => "SELECT artistas.nome, musicas.nome FROM musicas_has_artistas JOIN artistas ON artistas.id = musicas_has_artistas.artista_id JOIN musicas ON musica_id = musicas_has_artistas.musica_id WHERE artistas.nome = 'Mano Lima';",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Quantas músicas tem cada gênero?',
                'resposta' => "SELECT generos.descricao, COUNT(musicas.id) AS total FROM generos JOIN musicas ON generos.id = musicas.genero_id GROUP BY generos.id;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Quantas músicas cada cliente baixou?',
                'resposta' => "SELECT clientes.login, COUNT(musicas_has_clientes.id) AS total FROM musicas_has_clientes JOIN musicas ON musicas_has_clientes.musica_id = musicas.id JOIN clientes ON musicas_has_clientes.cliente_id = clientes.id GROUP BY clientes.id;",
                'valor' => 2,
            ],
            [
                'pergunta' => 'Qual o faturamento da minha empresa?',
                'resposta' => "SELECT SUM(planos.valor) total FROM planos JOIN clientes ON clientes.plano_id = planos.id;",
                'valor' => 2,
            ],
        ]);
    }
}
