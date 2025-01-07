<?php

namespace App\Http\Requests\CompanyRequest;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name' => 'required|string|max:255',
            'numberDocument' => 'required|string|max:20|unique:companies,numberDocument,NULL,id,deleted_at,NULL',
            'email' => 'nullable|email|max:255',
            'businessName' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'numberDocument.required' => 'El número de documento es obligatorio.',
            'numberDocument.string' => 'El número de documento debe ser una cadena de texto.',
            'numberDocument.max' => 'El número de documento no puede tener más de 20 caracteres.',
            'numberDocument.unique' => 'El número de documento ya está registrado.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'businessName.string' => 'El nombre comercial debe ser una cadena de texto.',
            'businessName.max' => 'El nombre comercial no puede tener más de 255 caracteres.',
        ];
    }
}
