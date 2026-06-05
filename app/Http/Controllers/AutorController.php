<?php

namespace App\Http\Controllers;

use App\Http\Resources\AutorResource;
use App\Models\Autor;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $autores = Autor::withCount('libros')
            ->when($request->query('nombre'), fn ($q, $v) => $q->where('nombre', 'ilike', "%{$v}%")
                ->orWhere('apellido', 'ilike', "%{$v}%"))
            ->paginate($request->integer('per_page', 10));

        return $this->success(AutorResource::collection($autores), 'Autores obtenidos exitosamente');
    }

    public function show(int $id): JsonResponse
    {
        $autor = Autor::with('libros')->find($id);

        if (! $autor) {
            return $this->notFound('Autor no encontrado');
        }

        return $this->success(new AutorResource($autor));
    }

    public function destroy(int $id): JsonResponse
    {
        $autor = Autor::withCount('libros')->find($id);

        if (! $autor) {
            return $this->notFound('Autor no encontrado');
        }

        if ($autor->libros_count > 0) {
            return $this->error(
                "No se puede eliminar el autor porque tiene {$autor->libros_count} libro(s) asociado(s).",
                422
            );
        }

        $autor->delete();

        return $this->success(null, 'Autor eliminado exitosamente');
    }
}
