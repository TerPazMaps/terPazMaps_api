<?php

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
        Schema::create('street_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('condition', 255); // Definindo tamanho
            $table->string('color', 7); // Adicionando a coluna color
            $table->timestamps(0); // Cria created_at e updated_at sem precisão
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('street_conditions');
    }
};
