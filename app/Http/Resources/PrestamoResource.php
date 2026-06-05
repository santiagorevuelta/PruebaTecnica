<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'estado'                    => $this->estado,
            'vencido'                   => $this->vencido,
            'dias_retraso'              => $this->dias_retraso,
            'fecha_prestamo'            => $this->fecha_prestamo?->format('Y-m-d'),
            'fecha_devolucion_estimada' => $this->fecha_devolucion_estimada?->format('Y-m-d'),
            'fecha_devolucion_real'     => $this->fecha_devolucion_real?->format('Y-m-d'),
            'libro'                     => new LibroResource($this->whenLoaded('libro')),
            'usuario'                   => $this->whenLoaded('usuario', fn () => [
                'id'     => $this->usuario->id,
                'nombre' => $this->usuario->nombre,
                'email'  => $this->usuario->email,
            ]),
        ];
    }
}
