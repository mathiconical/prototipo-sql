<?php

use App\Models\Cliente;
use App\Models\Musica;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musicas_has_clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Musica::class)->constrained();
            $table->foreignIdFor(Cliente::class)->constrained();
            $table->date('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musicas_has_clientes');
    }
};
