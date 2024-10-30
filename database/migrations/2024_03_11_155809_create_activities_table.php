<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('subclass_id');
            $table->string('name', 191)->nullable();
            // Aqui não especificamos o geometry diretamente pelo Laravel
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('level')->default(1);
            $table->timestamps();
            
            // Chaves estrangeiras
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('subclass_id')->references('id')->on('subclasses')->onDelete('cascade');
        });
        
        // Executar SQL bruto para adicionar a coluna geometry corretamente como 'geometry' e não 'geography'
        DB::statement('ALTER TABLE activities ADD COLUMN geometry geometry(Geometry, 4326) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Remover a coluna geometry antes de dropar a tabela
            $table->dropColumn('geometry');
        });
        
        Schema::dropIfExists('activities');
    }
};
