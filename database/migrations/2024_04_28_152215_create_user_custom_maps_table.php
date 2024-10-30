<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            // $table->geometry('geometry')->nullable(); // Coluna para geometria, se necessária
            // $table->geometry('center')->nullable();    // Coluna para o centro, se necessária
            $table->timestamps(); // Cria as colunas created_at e updated_at automaticamente

            // Chave estrangeira
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Adicionando onDelete se necessário
        });
        // Executar SQL bruto para adicionar a coluna geometry corretamente como 'geometry' e não 'geography'
        DB::statement('ALTER TABLE user_custom_maps ADD COLUMN center geometry(Geometry, 4326) NULL;');
        DB::statement('ALTER TABLE user_custom_maps ADD COLUMN geometry geometry(Geometry, 4326) NULL;');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('user_custom_maps', function (Blueprint $table) {
            // Remover a coluna geometry antes de dropar a tabela
            $table->dropColumn('center');
            $table->dropColumn('geometry');
        });
        Schema::dropIfExists('user_custom_maps');
    }
};
