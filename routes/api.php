<?php

use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\PrestamoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Autenticación (pública)
Route::post('/login',  [AuthApiController::class, 'login']);

// ──────────────────────────────────────────
// Rutas protegidas con Sanctum
// ──────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());

    // Autores
    Route::get('/autores',          [AutorController::class, 'index']);
    Route::get('/autores/{id}',     [AutorController::class, 'show']);
    Route::delete('/autores/{id}',  [AutorController::class, 'destroy']);

    // Libros
    Route::get('/libros',           [LibroController::class, 'index']);
    Route::get('/libros/{id}',      [LibroController::class, 'show']);
    Route::post('/libros',          [LibroController::class, 'store']);
    Route::put('/libros/{id}',      [LibroController::class, 'update']);
    Route::delete('/libros/{id}',   [LibroController::class, 'destroy']);

    // Préstamos
    Route::get('/prestamos',                   [PrestamoController::class, 'index']);
    Route::post('/prestamos',                  [PrestamoController::class, 'store']);
    Route::put('/prestamos/{id}/devolver',     [PrestamoController::class, 'devolver']);

    // Estadísticas para el dashboard
    Route::get('/estadisticas',  fn () => response()->json([
        'success' => true,
        'data'    => [
            'total_libros'       => \App\Models\Libro::count(),
            'libros_disponibles' => \App\Models\Libro::where('stock_disponible', '>', 0)->count(),
            'prestamos_activos'  => \App\Models\Prestamo::where('estado', 'activo')->count(),
            'prestamos_vencidos' => \App\Models\Prestamo::where('estado', 'vencido')->count(),
            'total_usuarios'     => \App\Models\Usuario::where('estado', 'activo')->count(),
        ],
    ]));
});
