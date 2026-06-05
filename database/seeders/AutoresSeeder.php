<?php

namespace Database\Seeders;

use App\Models\Autor;
use Illuminate\Database\Seeder;

class AutoresSeeder extends Seeder
{
    public function run(): void
    {
        $autores = [
            ['nombre' => 'Gabriel',    'apellido' => 'García Márquez', 'fecha_nacimiento' => '1927-03-06', 'nacionalidad' => 'Colombiana',  'biografia' => 'Premio Nobel de Literatura 1982. Padre del realismo mágico latinoamericano.'],
            ['nombre' => 'Jorge Luis', 'apellido' => 'Borges',         'fecha_nacimiento' => '1899-08-24', 'nacionalidad' => 'Argentina',   'biografia' => 'Maestro del cuento fantástico y los laberintos literarios.'],
            ['nombre' => 'Isabel',     'apellido' => 'Allende',        'fecha_nacimiento' => '1942-08-02', 'nacionalidad' => 'Chilena',     'biografia' => 'Autora de La casa de los espíritus. Una de las escritoras más leídas en español.'],
            ['nombre' => 'Mario',      'apellido' => 'Vargas Llosa',   'fecha_nacimiento' => '1936-03-28', 'nacionalidad' => 'Peruana',     'biografia' => 'Premio Nobel 2010. Renovador de la narrativa latinoamericana.'],
            ['nombre' => 'Julio',      'apellido' => 'Cortázar',       'fecha_nacimiento' => '1914-08-26', 'nacionalidad' => 'Argentina',   'biografia' => 'Innovador del cuento latinoamericano. Autor de Rayuela.'],
            ['nombre' => 'Pablo',      'apellido' => 'Neruda',         'fecha_nacimiento' => '1904-07-12', 'nacionalidad' => 'Chilena',     'biografia' => 'Premio Nobel de Literatura 1971. Poeta del amor y la naturaleza.'],
            ['nombre' => 'Octavio',    'apellido' => 'Paz',            'fecha_nacimiento' => '1914-03-31', 'nacionalidad' => 'Mexicana',    'biografia' => 'Premio Nobel 1990. Poeta y ensayista. Autor de El laberinto de la soledad.'],
            ['nombre' => 'Gabriela',   'apellido' => 'Mistral',        'fecha_nacimiento' => '1889-04-07', 'nacionalidad' => 'Chilena',     'biografia' => 'Primera latinoamericana en ganar el Premio Nobel de Literatura, en 1945.'],
            ['nombre' => 'Carlos',     'apellido' => 'Fuentes',        'fecha_nacimiento' => '1928-11-11', 'nacionalidad' => 'Mexicana',    'biografia' => 'Exponente del boom latinoamericano. Autor de La región más transparente.'],
            ['nombre' => 'Laura',      'apellido' => 'Restrepo',       'fecha_nacimiento' => '1950-01-15', 'nacionalidad' => 'Colombiana',  'biografia' => 'Periodista y escritora. Autora de Delirio, Premio Alfaguara 2004.'],
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }
    }
}
