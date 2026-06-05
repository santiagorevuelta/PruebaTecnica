<?php

namespace Database\Seeders;

use App\Models\Prestamo;
use Illuminate\Database\Seeder;

class PrestamoSeeder extends Seeder
{
    public function run(): void
    {
        $prestamos = [
            ['id_usuario' => 1,  'id_libro' => 1,  'fecha_prestamo' => '2024-01-10', 'fecha_devolucion_estimada' => '2024-01-24', 'fecha_devolucion_real' => '2024-01-22', 'estado' => 'devuelto'],
            ['id_usuario' => 2,  'id_libro' => 5,  'fecha_prestamo' => '2024-02-01', 'fecha_devolucion_estimada' => '2024-02-15', 'fecha_devolucion_real' => '2024-02-14', 'estado' => 'devuelto'],
            ['id_usuario' => 3,  'id_libro' => 9,  'fecha_prestamo' => '2024-03-05', 'fecha_devolucion_estimada' => '2024-03-19', 'fecha_devolucion_real' => null,          'estado' => 'vencido'],
            ['id_usuario' => 4,  'id_libro' => 3,  'fecha_prestamo' => '2024-04-12', 'fecha_devolucion_estimada' => '2024-04-26', 'fecha_devolucion_real' => '2024-04-25', 'estado' => 'devuelto'],
            ['id_usuario' => 5,  'id_libro' => 13, 'fecha_prestamo' => '2024-05-20', 'fecha_devolucion_estimada' => '2024-06-03', 'fecha_devolucion_real' => null,          'estado' => 'activo'],
            ['id_usuario' => 7,  'id_libro' => 7,  'fecha_prestamo' => '2024-06-01', 'fecha_devolucion_estimada' => '2024-06-15', 'fecha_devolucion_real' => '2024-06-10', 'estado' => 'devuelto'],
            ['id_usuario' => 8,  'id_libro' => 11, 'fecha_prestamo' => '2024-07-08', 'fecha_devolucion_estimada' => '2024-07-22', 'fecha_devolucion_real' => null,          'estado' => 'activo'],
            ['id_usuario' => 9,  'id_libro' => 19, 'fecha_prestamo' => '2024-08-14', 'fecha_devolucion_estimada' => '2024-08-28', 'fecha_devolucion_real' => null,          'estado' => 'activo'],
            ['id_usuario' => 10, 'id_libro' => 15, 'fecha_prestamo' => '2024-09-02', 'fecha_devolucion_estimada' => '2024-09-16', 'fecha_devolucion_real' => '2024-09-15', 'estado' => 'devuelto'],
            ['id_usuario' => 12, 'id_libro' => 17, 'fecha_prestamo' => '2024-10-10', 'fecha_devolucion_estimada' => '2024-10-24', 'fecha_devolucion_real' => null,          'estado' => 'vencido'],
        ];

        foreach ($prestamos as $prestamo) {
            Prestamo::create($prestamo);
        }
    }
}
