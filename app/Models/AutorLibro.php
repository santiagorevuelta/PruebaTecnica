<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AutorLibro extends Pivot
{
    public $timestamps = false;

    protected $table = 'autores_libros';

    protected $fillable = [
        'id_autor',
        'id_libros',
        'orden_autor',
    ];

    protected $casts = [
        'orden_autor' => 'integer',
    ];

    public function getPrincipalAttribute(): bool
    {
        return $this->orden_autor === 1;
    }

    public function autor()
    {
        return $this->belongsTo(Autor::class, 'id_autor');
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class, 'id_libros');
    }
}
