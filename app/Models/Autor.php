<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Autor extends Model
{
    public $timestamps = false;

    protected $table = 'autores';

    protected $fillable = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'nacionalidad',
        'biografia',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function libros(): BelongsToMany
    {
        return $this->belongsToMany(
            Libro::class,
            'autores_libros',
            'id_autor',
            'id_libros'
        )
            ->using(AutorLibro::class)
            ->withPivot('orden_autor')
            ->orderByPivot('orden_autor');
    }
}
