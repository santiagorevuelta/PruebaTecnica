<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('autores_libros', function (Blueprint $table) {
            $table->unsignedTinyInteger('orden_autor')->default(1)->after('id_libros');
        });
    }

    public function down(): void
    {
        Schema::table('autores_libros', function (Blueprint $table) {
            $table->dropColumn('orden_autor');
        });
    }
};
