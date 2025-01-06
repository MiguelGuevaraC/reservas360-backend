<?php

namespace App\Http\Requests\AuthenticateRequest;

use App\Http\Requests\StoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends StoreRequest
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
     * Obtener las reglas de validación para la solicitud.
     */
    public function rules()
    {
        return [
            "email" => [
                "required",
                'exists:users,email',
                "email",
                "regex:/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/",
            ],
            "password" => [
                "required",
                "regex:/^[a-zA-Z0-9!@#$%^&*()_+=-]*$/",
            ],
        ];
    }

    /**
     * Obtener los mensajes de error personalizados para la validación.
     */
    public function messages()
    {
        return [
            "email.required" => "El correo electrónico es obligatorio.",
            "email.email" => "El correo electrónico debe ser válido.",
            "email.exists" => "El correo electrónico no está registrado.",
            "email.regex" => "El correo electrónico no es válido.",
            "password.required" => "La contraseña es obligatoria.",
            "password.regex" => "La contraseña contiene caracteres no permitidos.",
        ];
    }

    /**
     * Verifica que solo los parámetros permitidos estén presentes.
     */
    public function validateResolved()
    {
        parent::validateResolved();

        // Parámetros permitidos
        $allowed = ['email', 'password'];

        // Obtiene todos los parámetros de la solicitud
        $extraParams = array_diff(array_keys($this->all()), $allowed);

        // Si existen parámetros adicionales, aborta la solicitud con error 400
        if (!empty($extraParams)) {
            // Mensaje de error para parámetros no permitidos
            
            abort(422, "Parámetros adicionales no permitidos: " . implode(', ', $extraParams));
        }
    }
}
