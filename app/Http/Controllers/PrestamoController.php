<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrestamoRequest;
use App\Http\Resources\PrestamoResource;
use App\Models\Libro;
use App\Models\Prestamo;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    use ApiResponse;

    // GET /api/prestamos
    public function index(Request $request): JsonResponse
    {
        $query = Prestamo::with(['libro', 'usuario']);

        if ($estado = $request->query('estado')) {
            $query->where('estado', $estado);
        }

        if ($usuarioId = $request->query('usuario_id')) {
            $query->where('id_usuario', $usuarioId);
        }

        if ($libroId = $request->query('libro_id')) {
            $query->where('id_libro', $libroId);
        }

        $prestamos = $query
            ->orderByDesc('fecha_prestamo')
            ->paginate($request->integer('per_page', 10));

        return $this->success(
            PrestamoResource::collection($prestamos),
            'Préstamos obtenidos exitosamente'
        );
    }

    // POST /api/prestamos
    public function store(StorePrestamoRequest $request): JsonResponse
    {
        $libro = Libro::find($request->id_libro);

        if ($libro->stock_disponible < 1) {
            return $this->error('El libro no tiene stock disponible', 422);
        }

        $prestamosActivos = Prestamo::where('id_usuario', $request->id_usuario)
            ->where('estado', 'activo')
            ->count();

        if ($prestamosActivos >= 3) {
            return $this->error('El usuario ya tiene 3 préstamos activos. Debe devolver uno antes de solicitar otro.', 422);
        }

        $prestamo = Prestamo::create([
            'id_usuario'                => $request->id_usuario,
            'id_libro'                  => $request->id_libro,
            'fecha_prestamo'            => $request->fecha_prestamo ?? now(),
            'fecha_devolucion_estimada' => $request->fecha_devolucion_estimada,
            'estado'                    => 'activo',
        ]);

        $libro->decrement('stock_disponible');

        $prestamo->load(['libro', 'usuario']);

        return $this->created(new PrestamoResource($prestamo), 'Préstamo registrado exitosamente');
    }

    // PUT /api/prestamos/{id}/devolver
    public function devolver(int $id): JsonResponse
    {
        $prestamo = Prestamo::with(['libro', 'usuario'])->find($id);

        if (! $prestamo) {
            return $this->notFound('Préstamo no encontrado');
        }

        if ($prestamo->estado === 'devuelto') {
            return $this->error('Este préstamo ya fue devuelto', 422);
        }

        $prestamo->update([
            'estado'                => 'devuelto',
            'fecha_devolucion_real' => now(),
        ]);

        $prestamo->libro->increment('stock_disponible');

        return $this->success(new PrestamoResource($prestamo), 'Préstamo marcado como devuelto');
    }
}
