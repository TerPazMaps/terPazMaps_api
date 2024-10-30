<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EnablePostgisExtension extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Habilitar a extensão PostGIS no banco de dados
        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remover a extensão PostGIS (opcional)
        DB::statement('DROP EXTENSION IF EXISTS postgis');
    }
}
