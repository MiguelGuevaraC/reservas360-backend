<?php

namespace App\Http\Requests\ProductRequest;

use App\Http\Requests\IndexRequest;

class IndexProductRequest extends IndexRequest
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
            'description' => 'nullable|string',
            'stock' => 'nullable|string',
            'price' => 'nullable|string',

            'category$name' => 'nullable|string',
           
            'from' => 'nullable|date',
            'to' => 'nullable|date',

        ];
    }
}
