<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/biblioteca')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

// ── Sección Biblioteca (Blade + JS vanilla) ──
Route::prefix('biblioteca')->group(function () {
    Route::view('/',          'biblioteca.dashboard')->name('biblioteca.dashboard');
    Route::view('/libros',    'biblioteca.libros')->name('biblioteca.libros');
    Route::view('/prestamos', 'biblioteca.prestamos')->name('biblioteca.prestamos');
});

require __DIR__.'/settings.php';
