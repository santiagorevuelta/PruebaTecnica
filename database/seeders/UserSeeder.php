<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['nombre' => 'Ana María',   'email' => 'ana.garcia@email.com',      'telefono' => '+57 301 234 5678', 'fecha_registro' => '2023-01-15', 'estado' => 'activo'],
            ['nombre' => 'Carlos',      'email' => 'carlos.lopez@email.com',    'telefono' => '+57 312 345 6789', 'fecha_registro' => '2023-02-20', 'estado' => 'activo'],
            ['nombre' => 'Valentina',   'email' => 'valen.martinez@email.com',  'telefono' => '+57 320 456 7890', 'fecha_registro' => '2023-03-10', 'estado' => 'activo'],
            ['nombre' => 'Luis Felipe', 'email' => 'luis.rodriguez@email.com',  'telefono' => '+57 315 567 8901', 'fecha_registro' => '2023-04-05', 'estado' => 'activo'],
            ['nombre' => 'Sofía',       'email' => 'sofia.hernandez@email.com', 'telefono' => '+57 300 678 9012', 'fecha_registro' => '2023-05-12', 'estado' => 'activo'],
            ['nombre' => 'Andrés',      'email' => 'andres.torres@email.com',   'telefono' => '+57 310 789 0123', 'fecha_registro' => '2023-06-18', 'estado' => 'inactivo'],
            ['nombre' => 'Camila',      'email' => 'camila.flores@email.com',   'telefono' => '+57 318 890 1234', 'fecha_registro' => '2023-07-22', 'estado' => 'activo'],
            ['nombre' => 'Diego',       'email' => 'diego.ramirez@email.com',   'telefono' => '+57 311 901 2345', 'fecha_registro' => '2023-08-30', 'estado' => 'activo'],
            ['nombre' => 'Isabella',    'email' => 'isa.sanchez@email.com',     'telefono' => '+57 302 012 3456', 'fecha_registro' => '2023-09-14', 'estado' => 'activo'],
            ['nombre' => 'Sebastián',   'email' => 'seba.diaz@email.com',       'telefono' => '+57 316 123 4567', 'fecha_registro' => '2023-10-01', 'estado' => 'activo'],
            ['nombre' => 'Mariana',     'email' => 'mari.morales@email.com',    'telefono' => '+57 317 234 5678', 'fecha_registro' => '2023-11-07', 'estado' => 'inactivo'],
            ['nombre' => 'Felipe',      'email' => 'fel.jimenez@email.com',     'telefono' => '+57 319 345 6789', 'fecha_registro' => '2023-12-03', 'estado' => 'activo'],
            ['nombre' => 'Daniela',     'email' => 'dani.ruiz@email.com',       'telefono' => '+57 304 456 7890', 'fecha_registro' => '2024-01-19', 'estado' => 'activo'],
            ['nombre' => 'Mateo',       'email' => 'mateo.vargas@email.com',    'telefono' => '+57 314 567 8901', 'fecha_registro' => '2024-02-25', 'estado' => 'activo'],
            ['nombre' => 'Luciana',     'email' => 'luci.castro@email.com',     'telefono' => '+57 303 678 9012', 'fecha_registro' => '2024-03-08', 'estado' => 'activo'],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
