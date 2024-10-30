<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('city', 191);
            // $table->geometry('geometry')->nullable();  // Tipo geometry do PostGIS
            $table->point('center', 4326)->nullable();  // Tipo point do PostGIS com SRID 4326
            $table->timestamps();
        });
        // Executar SQL bruto para adicionar a coluna geometry corretamente como 'geometry' e não 'geography'
        DB::statement('ALTER TABLE regions ADD COLUMN geometry geometry(Geometry, 4326) NULL;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regions', function (Blueprint $table) {
            // Remover a coluna geometry antes de dropar a tabela
            $table->dropColumn('geometry');
        });
        
        Schema::dropIfExists('regions');
    }
};
