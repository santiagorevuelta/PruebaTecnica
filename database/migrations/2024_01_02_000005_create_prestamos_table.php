<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->foreignId('id_libro')->constrained('libros');
            $table->date('fecha_prestamo')->default(now());
            $table->date('fecha_devolucion_estimada');
            $table->date('fecha_devolucion_real')->nullable();
            $table->enum('estado', ['activo', 'devuelto', 'vencido'])->default('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
