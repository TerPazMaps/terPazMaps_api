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
            $table->geometry('geometry');
            $table->longText('properties')->nullable();
            $table->string('color');
            $table->float('width', 10, 2);
            $table->tinyInteger('continuous')->default(1);
            $table->string('line_cap')->nullable();
            $table->string('line_dash_pattern')->nullable();
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('street_condition_id')->references('id')->on('street_conditions');
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
