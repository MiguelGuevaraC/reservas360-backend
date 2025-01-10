<?php

namespace App\Http\Requests\CompanyRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Cambia esto si necesitas autorización específica
    }

    public function rules()
    {
        $id = $this->route('id'); // Obtén el ID de la compañía desde la ruta.

        return [
            'name' => 'required|string|max:255',
            'ruc' => [
                'string',
                'max:20',
                Rule::unique('companies', 'ruc')
                    ->whereNull('deleted_at'), // Ignorar registros eliminados
            ],
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',

        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',

            'ruc.string' => 'El RUC debe ser una cadena de texto.',
            'ruc.max' => 'El RUC no puede tener más de 20 caracteres.',
            'ruc.unique' => 'El RUC ya está registrado en el sistema.',

            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no puede tener más de 255 caracteres.',

            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no puede tener más de 255 caracteres.',

            'telephone.string' => 'El teléfono fijo debe ser una cadena de texto.',
            'telephone.max' => 'El teléfono fijo no puede tener más de 255 caracteres.',

            'email.email' => 'Debe proporcionar una dirección de correo electrónico válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
        ];
    }

}
