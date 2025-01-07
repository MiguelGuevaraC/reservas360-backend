<?php

namespace App\Http\Requests\BranchOfficeRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchOfficeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'company_id' => 'exists:companies,id,deleted_at,NULL',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'address.required' => 'La dirección de documento es obligatorio.',
            'address.string' => 'La dirección de documento debe ser una cadena de texto.',
            'address.max' => 'La dirección de documento no puede tener más de 20 caracteres.',
            'company_id.exists' => 'El id Empresa proporcionado no existe.',
        ];
    }
    
}
