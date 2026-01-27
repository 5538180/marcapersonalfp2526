<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Las Migraciones definen los tipos de datos de los atributos del Modelo
     */
    public function up(): void
    {
        Schema::create('idiomas', function (Blueprint $table) {
            $table->id();
            $table->string('alpha2', 3);
            $table->string('alpha3t', 3);
            $table->string('alpha3b', 3);
            $table->string('english_name', 60);
            $table->string('native_name', 60);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idiomas');
    }
};

