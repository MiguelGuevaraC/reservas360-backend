<?php

namespace App\Http\Requests\PersonRequest;

use App\Http\Requests\IndexRequest;

class IndexPersonRequest extends IndexRequest
{
    public function rules(): array
    {
        return [
            'names' => 'nullable|string',
            'typeofDocument' => 'nullable|string',
            'fathersurname' => 'nullable|string',
            'mothersurname' => 'nullable|string',

            'documentNumber' => 'nullable|string',
            'businessName' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'origin' => 'nullable|string',
            'ocupation' => 'nullable|string',
            'from' => 'nullable|date',
            'to' => 'nullable|date',

        ];
    }
}
