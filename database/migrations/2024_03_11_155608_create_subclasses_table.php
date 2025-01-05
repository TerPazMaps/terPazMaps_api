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
        Schema::create('subclasses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->string('name', 191);
            $table->string('related_color', 191)->nullable();
            $table->timestamps(); // Cria as colunas created_at e updated_at automaticamente

            // Chave estrangeira
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade'); // Adicionando onDelete se necessário
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('subclasses');
    }
};
