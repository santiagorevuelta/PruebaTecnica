<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestamo extends Model
{
    public $timestamps = false;

    protected $table = 'prestamos';

    protected $fillable = [
        'id_usuario',
        'id_libro',
        'fecha_prestamo',
        'fecha_devolucion_estimada',
        'fecha_devolucion_real',
        'estado',
    ];

    protected $casts = [
        'fecha_prestamo'            => 'date',
        'fecha_devolucion_estimada' => 'date',
        'fecha_devolucion_real'     => 'date',
    ];

    public function getVencidoAttribute(): bool
    {
        return $this->estado === 'vencido'
            || ($this->estado === 'activo' && $this->fecha_devolucion_estimada < now());
    }

    public function getDiasRetrasoAttribute(): int
    {
        if (! $this->fecha_devolucion_real || ! $this->fecha_devolucion_estimada) {
            return 0;
        }

        return max(0, $this->fecha_devolucion_estimada->diffInDays($this->fecha_devolucion_real, false) * -1);
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('estado', 'activo');
    }

    public function scopeVencidos(Builder $query): Builder
    {
        return $query->where('estado', 'vencido');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }
}
