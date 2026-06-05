<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('isbn', 20)->unique()->nullable();
            $table->unsignedSmallInteger('year_publicacion')->nullable();
            $table->unsignedInteger('numero_paginas')->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('stock_disponible')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
