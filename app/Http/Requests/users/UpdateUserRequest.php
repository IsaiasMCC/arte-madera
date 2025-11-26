<?php

namespace App\Http\Requests\users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ci' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email')
                    ->ignore($this->route('usuario')),
            ],
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,id',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'ci.required' => 'El campo CI es obligatorio.',
            'name.required' => 'El campo Nombre es obligatorio.',
            'lastname.required' => 'El campo Apellidos es obligatorio.',

            'email.required' => 'El campo Email es obligatorio.',
            'email.email' => 'El formato del Email no es válido.',
            'email.unique' => 'El Email ya está en uso.',
            
            // 'password.required' => 'El campo Contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'role.required' => 'Debe seleccionar un rol válido.',
            'estado.required' => 'El campo Estado es obligatorio.',
        ];
    }
}
