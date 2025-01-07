<?php

namespace App\Http\Requests\BranchOfficeRequest;

use Illuminate\Foundation\Http\FormRequest;

class IndexBranchOfficeRequest extends FormRequest
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
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            
            'company$businessName' => 'nullable|string',
           
            'from' => 'nullable|date',
            'to' => 'nullable|date',

        ];
    }
}
