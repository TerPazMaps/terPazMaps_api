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
        Schema::create('feedback_streets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('street_id');  // Removido o unique
            $table->unsignedBigInteger('street_condition_id');
            $table->timestamps();

            // Chaves estrangeiras com ON DELETE CASCADE
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('street_id')->references('id')->on('streets')->onDelete('cascade');
            $table->foreign('street_condition_id')->references('id')->on('street_conditions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_streets');
    }
};
