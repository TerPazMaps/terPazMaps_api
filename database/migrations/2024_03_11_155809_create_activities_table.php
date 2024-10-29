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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('subclass_id');
            $table->string('name', 191)->nullable();
            $table->geometry('geometry')->nullable(); // Coluna para geometria, se necessária
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('level')->default(1);
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade'); // Adicionando onDelete se necessário
            $table->foreign('subclass_id')->references('id')->on('subclasses')->onDelete('cascade'); // Adicionando onDelete se necessário
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
