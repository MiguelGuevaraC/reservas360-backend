<?php

namespace App\Http\Requests\ProductRequest;

use App\Http\Requests\StoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends StoreRequest
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
            'description' => 'nullable|string|max:255', // Puede ser nulo
            'photo' => 'nullable|string', // Puede ser una URL o cadena vacía
          
            'price' => 'required|numeric|min:0', // Precio debe ser mayor o igual a 0
            'status' => 'required|boolean', // Asegurarse de que sea true o false
            'category_id' => 'required|exists:categories,id,deleted_at,NULL', // Validar relación existente
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.numeric' => 'El stock debe ser un valor numérico.',
      
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un valor numérico.',
            'price.min' => 'El precio no puede ser menor a 0.',
            'status.required' => 'El estado es obligatorio.',
            'status.boolean' => 'El estado debe ser verdadero o falso.',
            'category_id.required' => 'El ID de la categoría es obligatorio.',
            'category_id.exists' => 'La categoría proporcionada no existe.',
        ];
    }

}
