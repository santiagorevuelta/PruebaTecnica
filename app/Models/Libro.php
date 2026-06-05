<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Libro extends Model
{
    public $timestamps = false;

    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'isbn',
        'year_publicacion',
        'numero_paginas',
        'descripcion',
        'stock_disponible',
    ];

    protected $casts = [
        'year_publicacion'  => 'integer',
        'numero_paginas'    => 'integer',
        'stock_disponible'  => 'integer',
    ];

    public function getDisponibleAttribute(): bool
    {
        return $this->stock_disponible > 0;
    }

    // Scope: libros con stock disponible
    public function scopeDisponibles(Builder $query): Builder
    {
        return $query->where('stock_disponible', '>', 0);
    }

    // Scope: libros por año de publicación
    public function scopePorAnio(Builder $query, int $anio): Builder
    {
        return $query->where('year_publicacion', $anio);
    }

    // Scope: libros de un autor específico
    public function scopePorAutor(Builder $query, int $autorId): Builder
    {
        return $query->whereHas('autores', fn (Builder $q) => $q->where('autores.id', $autorId));
    }

    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(
            Autor::class,
            'autores_libros',
            'id_libros',
            'id_autor'
        )
        ->using(AutorLibro::class)
        ->withPivot('orden_autor')
        ->orderByPivot('orden_autor');
    }

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class, 'id_libro');
    }
}
