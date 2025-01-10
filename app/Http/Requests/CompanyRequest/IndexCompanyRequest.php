<?php

namespace App\Http\Requests\CompanyRequest;

use Illuminate\Foundation\Http\FormRequest;

class IndexCompanyRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'uuid' => 'nullable|string',
            'ruc' => 'nullable|string',
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email' => 'nullable|email',

            'from' => 'nullable|date',
            'to' => 'nullable|date',

        ];
    }
    public function messages(): array
    {
        return [
            'uuid.string' => 'El campo "UUID" debe ser una cadena de texto.',
            'ruc.string' => 'El campo "RUC" debe ser una cadena de texto.',
            'name.string' => 'El campo "nombre" debe ser una cadena de texto.',
            'address.string' => 'El campo "dirección" debe ser una cadena de texto.',
            'phone.string' => 'El campo "teléfono" debe ser una cadena de texto.',
            'telephone.string' => 'El campo "teléfono fijo" debe ser una cadena de texto.',
            'email.email' => 'El campo "correo electrónico" debe ser una dirección de correo válida.',
            'from.date' => 'El campo "desde" debe ser una fecha válida.',
            'to.date' => 'El campo "hasta" debe ser una fecha válida.',
        ];
    }
    

}
