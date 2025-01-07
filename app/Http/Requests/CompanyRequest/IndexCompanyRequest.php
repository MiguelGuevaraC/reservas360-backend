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
            'numberDocument' => 'nullable|string',
            'businessName' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
           
            'from' => 'nullable|date',
            'to' => 'nullable|date',

        ];
    }
}
