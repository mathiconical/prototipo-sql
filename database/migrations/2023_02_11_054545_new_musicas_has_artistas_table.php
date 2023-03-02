<?php

use App\Models\Artista;
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
        Schema::create('musicas_has_artistas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Musica::class)->constrained();
            $table->foreignIdFor(Artista::class)->constrained();
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
        Schema::dropIfExists('musicas_has_artistas');
    }
};
