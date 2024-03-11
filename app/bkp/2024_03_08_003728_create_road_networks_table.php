<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('road_networks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->string('name', 191);
            $table->geometryCollection('geometry')->nullable();
            $table->longText('properties')->nullable();
            $table->string('color', 191);
            $table->float('width', 10, 2);
            $table->tinyInteger('continuous')->default(1);
            $table->string('line_cap', 191)->nullable();
            $table->string('line_dash_pattern', 191)->nullable();
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('road_networks');
    }
};
