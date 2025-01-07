<?php

namespace App\Http\Requests\CompanyRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
        $id = $this->route('id'); // Obtén el ID de la compañía desde la ruta.

        return [
            'name' => 'string|max:255',
            'numberDocument' => [
                'string',
                'max:20',
                Rule::unique('companies', 'numberDocument')
                    ->ignore($id) // Ignorar el ID actual
                    ->whereNull('deleted_at'), // Ignorar registros eliminados
            ],
            'email' => 'email|max:255',
            'businessName' => 'string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'numberDocument.string' => 'El número de documento debe ser una cadena de texto.',
            'numberDocument.max' => 'El número de documento no puede tener más de 20 caracteres.',
            'numberDocument.unique' => 'El número de documento ya está registrado en el sistema.',
            'email.email' => 'Debe proporcionar una dirección de correo electrónico válida.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'businessName.string' => 'El nombre comercial debe ser una cadena de texto.',
            'businessName.max' => 'El nombre comercial no puede tener más de 255 caracteres.',
        ];
    }
    
}
