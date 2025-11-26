<?php

namespace App\Http\Requests\productos;

use Illuminate\Foundation\Http\FormRequest;

class ProductoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'foto' => 'nullable|image|max:2048', // Opcional, máximo 2MB
            // 'estado' => 'sometimes|boolean',
            'categoria_id' => 'required|exists:categorias,id',
            'tienda_id' => 'required|exists:tiendas,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
            'precio.min' => 'El precio no puede ser negativo.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'foto.image' => 'La foto debe ser una imagen válida.',
            'foto.max' => 'La imagen no puede superar los 2MB.',
            // 'estado.boolean' => 'El estado debe ser verdadero o falso.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'tienda_id.required' => 'Debe seleccionar una tienda.',
            'tienda_id.exists' => 'La tienda seleccionada no es válida.',
        ];
    }
}
