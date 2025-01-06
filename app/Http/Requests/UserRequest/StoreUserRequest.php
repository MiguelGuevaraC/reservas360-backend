<?php

namespace App\Http\Requests\UserRequest;

use App\Http\Requests\StoreRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'regex:/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/',
                'string',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'password' => 'required',

            'names' => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'fathersurname' => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'mothersurname' => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15|regex:/^\+?[0-9\s\-]+$/',

            'documentNumber' => [
                'required',
                'exists:people,documentNumber,deleted_at,NULL',
                function ($attribute, $value, $fail) {
                    $person = \App\Models\Person::where('documentNumber', $value)
                        ->whereNull('deleted_at')
                        ->first();

                    if ($person && \App\Models\User::where('person_id', $person->id)->whereNull('deleted_at')->exists()) {
                        $fail('La persona ya tiene un usuario asociado.');
                    }
                },
            ],
        ];
    }

    /**
     * Obtén los mensajes personalizados para las reglas de validación.
     */
    public function messages()
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Por favor, introduce un correo electrónico válido.',
            'email.regex' => 'El correo electrónico debe tener un formato correcto (e.g., usuario@dominio.com).',
            'email.string' => 'El correo electrónico debe ser un texto válido.',
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'password.required' => 'La contraseña es obligatoria.',
            'names.string' => 'El campo "Nombres" debe ser un texto válido.',
            'names.max' => 'El campo "Nombres" no debe exceder los 255 caracteres.',
            'names.regex' => 'El campo "Nombres" solo debe contener letras y espacios.',
            'fathersurname.string' => 'El campo "Apellido Paterno" debe ser un texto válido.',
            'fathersurname.max' => 'El campo "Apellido Paterno" no debe exceder los 255 caracteres.',
            'fathersurname.regex' => 'El campo "Apellido Paterno" solo debe contener letras y espacios.',
            'mothersurname.string' => 'El campo "Apellido Materno" debe ser un texto válido.',
            'mothersurname.max' => 'El campo "Apellido Materno" no debe exceder los 255 caracteres.',
            'mothersurname.regex' => 'El campo "Apellido Materno" solo debe contener letras y espacios.',
            'address.string' => 'El campo "Dirección" debe ser un texto válido.',
            'address.max' => 'El campo "Dirección" no debe exceder los 255 caracteres.',
            'phone.string' => 'El campo "Teléfono" debe ser un texto válido.',
            'phone.max' => 'El campo "Teléfono" no debe exceder los 15 caracteres.',
            'phone.regex' => 'El campo "Teléfono" debe contener solo números, espacios, guiones o un prefijo "+" válido.',
            'documentNumber.required' => 'El número de documento es obligatorio.',
            'documentNumber.exists' => 'El número de documento proporcionado no existe o está asociado a un registro eliminado.',
        ];
    }
}
