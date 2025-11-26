<?php

namespace App\Http\Requests\tiendas;

use Illuminate\Foundation\Http\FormRequest;

class TiendaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Puedes agregar permisos aquí
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'ubicacion' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la tienda es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'nit.string' => 'El NIT debe ser un texto válido.',
            'nit.max' => 'El NIT no puede superar los 50 caracteres.',
            'telefono.string' => 'El teléfono debe ser un texto válido.',
            'telefono.max' => 'El teléfono no puede superar los 50 caracteres.',
            'ubicacion.string' => 'La ubicación debe ser un texto válido.',
            'ubicacion.max' => 'La ubicación no puede superar los 255 caracteres.',
        ];
    }
}
