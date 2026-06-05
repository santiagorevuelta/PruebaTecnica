<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestamoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_usuario'               => ['required', 'integer', 'exists:usuarios,id'],
            'id_libro'                 => ['required', 'integer', 'exists:libros,id'],
            'fecha_prestamo'           => ['nullable', 'date'],
            'fecha_devolucion_estimada'=> ['required', 'date', 'after:fecha_prestamo'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_usuario.exists'               => 'El usuario no existe.',
            'id_libro.exists'                 => 'El libro no existe.',
            'fecha_devolucion_estimada.after' => 'La fecha de devolución debe ser posterior al préstamo.',
        ];
    }
}
