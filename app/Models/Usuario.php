<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    public $timestamps = false;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'fecha_registro',
        'estado',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    public function getActivoAttribute(): bool
    {
        return $this->estado === 'activo';
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function prestamos(): HasMany
    {
        return $this->hasMany(Prestamo::class, 'id_usuario');
    }

    public function prestamosActivos(): HasMany
    {
        return $this->hasMany(Prestamo::class, 'id_usuario')->where('estado', 'activo');
    }
}
