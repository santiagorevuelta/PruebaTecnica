<?php

namespace Database\Seeders;

use App\Models\Autor;
use App\Models\Libro;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $librosData = [
            ['titulo' => 'Cien años de soledad',              'isbn' => '978-0-06-088328-7', 'year_publicacion' => 1967, 'numero_paginas' => 432, 'descripcion' => 'La saga de la familia Buendía en el pueblo mítico de Macondo.',                        'stock_disponible' => 5],
            ['titulo' => 'El amor en los tiempos del cólera', 'isbn' => '978-0-14-028622-0', 'year_publicacion' => 1985, 'numero_paginas' => 368, 'descripcion' => 'Historia de amor que dura más de cincuenta años.',                                      'stock_disponible' => 3],
            ['titulo' => 'Ficciones',                         'isbn' => '978-0-8021-5079-4', 'year_publicacion' => 1944, 'numero_paginas' => 224, 'descripcion' => 'Colección de cuentos fantásticos que exploran laberintos y paradojas.',                 'stock_disponible' => 7],
            ['titulo' => 'El Aleph',                          'isbn' => '978-0-14-028628-2', 'year_publicacion' => 1949, 'numero_paginas' => 208, 'descripcion' => 'Cuentos que mezclan lo cotidiano con lo fantástico e infinito.',                        'stock_disponible' => 4],
            ['titulo' => 'La casa de los espíritus',          'isbn' => '978-1-5011-0385-1', 'year_publicacion' => 1982, 'numero_paginas' => 448, 'descripcion' => 'Saga familiar que abarca generaciones marcadas por la política chilena.',               'stock_disponible' => 6],
            ['titulo' => 'Eva Luna',                          'isbn' => '978-0-553-28010-5', 'year_publicacion' => 1987, 'numero_paginas' => 352, 'descripcion' => 'Historia de una narradora que usa la imaginación para sobrevivir.',                     'stock_disponible' => 2],
            ['titulo' => 'La ciudad y los perros',            'isbn' => '978-84-322-0005-6', 'year_publicacion' => 1963, 'numero_paginas' => 440, 'descripcion' => 'Novela sobre la violencia y el poder en un colegio militar de Lima.',                  'stock_disponible' => 4],
            ['titulo' => 'Conversación en La Catedral',       'isbn' => '978-84-322-1908-9', 'year_publicacion' => 1969, 'numero_paginas' => 600, 'descripcion' => 'Retrato de la corrupción moral del Perú bajo la dictadura de Odría.',                  'stock_disponible' => 1],
            ['titulo' => 'Rayuela',                           'isbn' => '978-84-376-0439-7', 'year_publicacion' => 1963, 'numero_paginas' => 736, 'descripcion' => 'Novela experimental que puede leerse en distintos órdenes.',                            'stock_disponible' => 3],
            ['titulo' => 'Bestiario',                         'isbn' => '978-84-663-1118-0', 'year_publicacion' => 1951, 'numero_paginas' => 192, 'descripcion' => 'Primer libro de cuentos de Cortázar, con criaturas fantásticas.',                      'stock_disponible' => 5],
            ['titulo' => 'Veinte poemas de amor',             'isbn' => '978-84-376-0122-8', 'year_publicacion' => 1924, 'numero_paginas' => 112, 'descripcion' => 'Poemario que catapultó a Neruda como voz del amor apasionado.',                        'stock_disponible' => 8],
            ['titulo' => 'Canto general',                     'isbn' => '978-84-206-6700-2', 'year_publicacion' => 1950, 'numero_paginas' => 480, 'descripcion' => 'Epopeya poética sobre América Latina y sus pueblos.',                                  'stock_disponible' => 3],
            ['titulo' => 'El laberinto de la soledad',        'isbn' => '978-0-8021-5042-8', 'year_publicacion' => 1950, 'numero_paginas' => 320, 'descripcion' => 'Ensayo que analiza la identidad y el carácter del pueblo mexicano.',                   'stock_disponible' => 4],
            ['titulo' => 'Piedra de sol',                     'isbn' => '978-968-16-0420-4', 'year_publicacion' => 1957, 'numero_paginas' =>  80, 'descripcion' => 'Poema cíclico basado en el calendario azteca.',                                        'stock_disponible' => 2],
            ['titulo' => 'Desolación',                        'isbn' => '978-956-239-350-7', 'year_publicacion' => 1922, 'numero_paginas' => 240, 'descripcion' => 'Primer poemario de Gabriela Mistral, lleno de dolor y espiritualidad.',                'stock_disponible' => 6],
            ['titulo' => 'Ternura',                           'isbn' => '978-956-239-351-4', 'year_publicacion' => 1924, 'numero_paginas' => 160, 'descripcion' => 'Colección de poemas dedicados a los niños y la infancia.',                             'stock_disponible' => 4],
            ['titulo' => 'La región más transparente',        'isbn' => '978-84-663-0122-8', 'year_publicacion' => 1958, 'numero_paginas' => 464, 'descripcion' => 'Fresco de la sociedad mexicana postrevolucionaria.',                                   'stock_disponible' => 3],
            ['titulo' => 'Aura',                              'isbn' => '978-968-16-0187-6', 'year_publicacion' => 1962, 'numero_paginas' =>  64, 'descripcion' => 'Novela corta de fantasía y seducción ambientada en el México urbano.',                 'stock_disponible' => 7],
            ['titulo' => 'Delirio',                           'isbn' => '978-84-204-6987-2', 'year_publicacion' => 2004, 'numero_paginas' => 320, 'descripcion' => 'Novela sobre la locura, la memoria y la violencia en Colombia.',                       'stock_disponible' => 5],
            ['titulo' => 'La multitud errante',               'isbn' => '978-84-204-6400-6', 'year_publicacion' => 2001, 'numero_paginas' => 192, 'descripcion' => 'Historia de amor en medio del desplazamiento forzado en Colombia.',                    'stock_disponible' => 3],
        ];

        // Guardamos las instancias creadas para usar sus IDs reales
        $libros = [];
        foreach ($librosData as $data) {
            $libros[] = Libro::create($data);
        }

        // Cargamos los autores en el orden en que fueron insertados
        $autores = Autor::orderBy('id')->get();

        // Índice 0-based: autor[0] = García Márquez, autor[1] = Borges, etc.
        $relaciones = [
            0 => [0, 1, 2],        // García Márquez → libros 1,2,3
            1 => [2, 3],           // Borges         → libros 3,4  (coautor en Ficciones)
            2 => [4, 5],           // Allende
            3 => [6, 7],           // Vargas Llosa
            4 => [8, 9],           // Cortázar
            5 => [10, 11, 13],     // Neruda         (coautor en Piedra de sol)
            6 => [12, 13],         // Octavio Paz    (coautor en Piedra de sol)
            7 => [14, 15],         // Gabriela Mistral
            8 => [16, 17],         // Carlos Fuentes
            9 => [18, 19],         // Laura Restrepo
        ];

        foreach ($relaciones as $autorIdx => $libroIdxs) {
            $autor = $autores[$autorIdx];
            // Para cada libro, el orden_autor refleja si es autor principal (1) o secundario (2+)
            $attach = [];
            foreach ($libroIdxs as $orden => $libroIdx) {
                $attach[$libros[$libroIdx]->id] = ['orden_autor' => $orden + 1];
            }
            $autor->libros()->syncWithoutDetaching($attach);
        }
    }
}
