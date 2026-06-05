<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AutorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'nombre_completo'  => $this->nombre_completo,
            'nombre'           => $this->nombre,
            'apellido'         => $this->apellido,
            'nacionalidad'     => $this->nacionalidad,
            'fecha_nacimiento' => $this->fecha_nacimiento?->format('Y-m-d'),
            'orden_autor'      => $this->whenPivotLoaded('autores_libros', fn () => $this->pivot->orden_autor),
        ];
    }
}
