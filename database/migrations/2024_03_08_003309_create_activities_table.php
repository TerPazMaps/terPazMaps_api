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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('subclass_id');
            $table->string('name', 191)->nullable();
            $table->point('geometry')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->unsignedBigInteger('level')->default(1);
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('subclass_id')->references('id')->on('subclasses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
