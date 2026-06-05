<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $libroId = $this->route('libro');

        return [
            'titulo'           => ['sometimes', 'string', 'max:255'],
            'isbn'             => ['sometimes', 'nullable', 'string', 'max:20', "unique:libros,isbn,{$libroId}"],
            'year_publicacion' => ['sometimes', 'nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'numero_paginas'   => ['sometimes', 'nullable', 'integer', 'min:1'],
            'descripcion'      => ['sometimes', 'nullable', 'string'],
            'stock_disponible' => ['sometimes', 'integer', 'min:0'],
            'autores'          => ['sometimes', 'nullable', 'array'],
            'autores.*'        => ['integer', 'exists:autores,id'],
        ];
    }
}
