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
        Schema::create('streets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('street_condition_id')->nullable();
            $table->geometry('geometry', '4326'); // Definindo SRID 4326
            $table->longText('properties')->nullable();
            $table->string('color'); // Se quiser definir um tamanho, use $table->string('color', 191);
            $table->decimal('width', 10, 2); // Alterado para decimal para refletir o SQL original
            $table->boolean('continuous')->default(true); // Alterado para boolean
            $table->string('line_cap')->nullable();
            $table->string('line_dash_pattern')->nullable();
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('street_condition_id')->references('id')->on('street_conditions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streets');
    }
};
