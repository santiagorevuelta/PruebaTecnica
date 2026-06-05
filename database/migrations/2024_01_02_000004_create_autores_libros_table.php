<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autores_libros', function (Blueprint $table) {
            $table->unsignedBigInteger('id_autor');
            $table->unsignedBigInteger('id_libros');

            $table->primary(['id_autor', 'id_libros']);

            $table->foreign('id_autor')
                ->references('id')->on('autores')
                ->onDelete('cascade');

            $table->foreign('id_libros')
                ->references('id')->on('libros')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autores_libros');
    }
};
