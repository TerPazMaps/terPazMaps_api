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
        Schema::create('feedback_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('subclass_id');
            $table->string('name', 191)->nullable();
            // $table->geometry('geometry')->nullable();  // Alterado para geometry do PostGIS
            $table->timestamps();
            
            // Definindo as chaves estrangeiras com ON DELETE CASCADE para remover feedbacks relacionados
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('subclass_id')->references('id')->on('subclasses')->onDelete('cascade');
        });
        // Executar SQL bruto para adicionar a coluna geometry corretamente como 'geometry' e não 'geography'
        DB::statement('ALTER TABLE feedback_activities ADD COLUMN geometry geometry(Geometry, 4326) NULL;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_activities', function (Blueprint $table) {
            // Remover a coluna geometry antes de dropar a tabela
            $table->dropColumn('geometry');
        });
        Schema::dropIfExists('feedback_activities');
    }
};
