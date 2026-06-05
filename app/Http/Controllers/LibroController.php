<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLibroRequest;
use App\Http\Requests\UpdateLibroRequest;
use App\Http\Resources\LibroResource;
use App\Models\Libro;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    use ApiResponse;

    // GET /api/libros
    public function index(Request $request): JsonResponse
    {
        $query = Libro::with('autores');

        if ($titulo = $request->query('titulo')) {
            $query->where('titulo', 'ilike', "%{$titulo}%");
        }

        if ($anio = $request->query('anio')) {
            $query->porAnio((int) $anio);
        }

        if ($autorId = $request->query('autor_id')) {
            $query->porAutor((int) $autorId);
        }

        if ($request->boolean('solo_disponibles')) {
            $query->disponibles();
        }

        $libros = $query->paginate($request->integer('per_page', 10));

        return $this->success(
            LibroResource::collection($libros),
            'Libros obtenidos exitosamente'
        );
    }

    // GET /api/libros/{id}
    public function show(int $id): JsonResponse
    {
        $libro = Libro::with('autores')->find($id);

        if (! $libro) {
            return $this->notFound('Libro no encontrado');
        }

        return $this->success(new LibroResource($libro));
    }

    // POST /api/libros
    public function store(StoreLibroRequest $request): JsonResponse
    {
        $libro = Libro::create($request->safe()->except('autores'));

        if ($request->filled('autores')) {
            $libro->autores()->sync($request->input('autores'));
        }

        $libro->load('autores');

        return $this->created(new LibroResource($libro), 'Libro creado exitosamente');
    }

    // PUT /api/libros/{id}
    public function update(UpdateLibroRequest $request, int $id): JsonResponse
    {
        $libro = Libro::find($id);

        if (! $libro) {
            return $this->notFound('Libro no encontrado');
        }

        $libro->update($request->safe()->except('autores'));

        if ($request->has('autores')) {
            $libro->autores()->sync($request->input('autores', []));
        }

        $libro->load('autores');

        return $this->success(new LibroResource($libro), 'Libro actualizado exitosamente');
    }

    // DELETE /api/libros/{id}  (soft delete)
    public function destroy(int $id): JsonResponse
    {
        $libro = Libro::find($id);

        if (! $libro) {
            return $this->notFound('Libro no encontrado');
        }

        $libro->delete();

        return $this->success(null, 'Libro eliminado exitosamente');
    }
}
