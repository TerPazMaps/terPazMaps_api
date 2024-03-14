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
        Schema::create('icons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subclasse_id')->nullable();
            $table->string('disk_name', 191);
            $table->string('file_name', 191);
            $table->bigInteger('file_size');
            $table->string('content_type', 191);
            $table->string('title', 191)->nullable();
            $table->text('description')->nullable();
            $table->string('field', 191)->nullable();
            $table->string('attachment_type', 191)->nullable();
            $table->boolean('is_public')->default(true);
            $table->integer('sort_order')->nullable();
            $table->timestamps();

              // Chaves estrangeiras
              $table->foreign('subclasse_id')->references('id')->on('subclasses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('icons');
    }
};

