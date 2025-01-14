<?php

namespace App\Http\Requests\BranchOfficeRequest;

use App\Http\Requests\StoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchOfficeRequest extends StoreRequest
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
            'ruc' => 'required|string|max:255',
            'brand_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'company_id' => 'exists:companies,id,deleted_at,NULL',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'ruc.required' => 'El RUC es obligatorio.',
            'ruc.string' => 'El RUC debe ser una cadena de texto.',
            'ruc.max' => 'El RUC no puede tener más de 255 caracteres.',
            'brand_name.required' => 'El nombre de la marca es obligatorio.',
            'brand_name.string' => 'El nombre de la marca debe ser una cadena de texto.',
            'brand_name.max' => 'El nombre de la marca no puede tener más de 255 caracteres.',
            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no puede tener más de 255 caracteres.',
            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no puede tener más de 255 caracteres.',
            'telephone.string' => 'El teléfono fijo debe ser una cadena de texto.',
            'telephone.max' => 'El teléfono fijo no puede tener más de 255 caracteres.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'company_id.exists' => 'El ID de la empresa proporcionado no existe o está marcado como eliminado.',
        ];
    }

}
