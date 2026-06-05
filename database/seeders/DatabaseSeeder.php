<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Limpia en orden correcto respetando FKs y reinicia secuencias
        DB::statement('TRUNCATE prestamos, autores_libros, libros, autores, usuarios RESTART IDENTITY CASCADE');

        $this->call([
            AutoresSeeder::class,
            LibroSeeder::class,
            UserSeeder::class,
            PrestamoSeeder::class,
        ]);
    }
}
