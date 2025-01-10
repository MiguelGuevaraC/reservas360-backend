<?php

namespace App\Http\Requests\CategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class IndexCategoryRequest extends FormRequest
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
            'name' => 'nullable|string',
            'brand_name' => 'nullable|string',
            'from' => 'nullable|date',
            'to' => 'nullable|date',

        ];
    }
    public function messages(): array
    {
        return [
            'uuid.string' => 'El campo "UUID" debe ser una cadena de texto.',
            'name.string' => 'El campo "nombre" debe ser una cadena de texto.',
    
            'from.date' => 'El campo "desde" debe ser una fecha válida.',
            'to.date' => 'El campo "hasta" debe ser una fecha válida.',
        ];
    }

}
