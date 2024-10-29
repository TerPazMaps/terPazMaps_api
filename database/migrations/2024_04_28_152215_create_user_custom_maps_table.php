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
    public function up(): void
    {
        Schema::create('user_custom_maps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name', 191);
            $table->geometry('geometry')->nullable(); // Coluna para geometria, se necessária
            $table->geometry('center')->nullable();    // Coluna para o centro, se necessária
            $table->timestamps(); // Cria as colunas created_at e updated_at automaticamente

            // Chave estrangeira
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Adicionando onDelete se necessário
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_custom_maps');
    }
};
