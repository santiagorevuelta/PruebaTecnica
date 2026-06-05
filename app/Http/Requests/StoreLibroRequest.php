<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'           => ['required', 'string', 'max:255'],
            'isbn'             => ['nullable', 'string', 'max:20', 'unique:libros,isbn'],
            'year_publicacion' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'numero_paginas'   => ['nullable', 'integer', 'min:1'],
            'descripcion'      => ['nullable', 'string'],
            'stock_disponible' => ['required', 'integer', 'min:0'],
            'autores'          => ['nullable', 'array'],
            'autores.*'        => ['integer', 'exists:autores,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required'          => 'El título es obligatorio.',
            'isbn.unique'              => 'Ya existe un libro con ese ISBN.',
            'year_publicacion.max'     => 'El año no puede ser futuro.',
            'stock_disponible.required'=> 'El stock es obligatorio.',
            'autores.*.exists'         => 'Uno o más autores no existen.',
        ];
    }
}
