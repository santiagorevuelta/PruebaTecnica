<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'titulo'           => $this->titulo,
            'isbn'             => $this->isbn,
            'year_publicacion' => $this->year_publicacion,
            'numero_paginas'   => $this->numero_paginas,
            'descripcion'      => $this->descripcion,
            'stock_disponible' => $this->stock_disponible,
            'disponible'       => $this->disponible,
            'autores'          => AutorResource::collection($this->whenLoaded('autores')),
            'deleted_at'       => $this->when($this->deleted_at, $this->deleted_at?->toDateTimeString()),
        ];
    }
}
