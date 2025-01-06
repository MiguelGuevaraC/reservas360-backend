<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'typeofDocument' => $this->typeofDocument,
            'documentNumber' => $this->documentNumber,
            'businessName' => $this->businessName,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'origin' => $this->origin,
            'ocupation' => $this->ocupation,
        ];
    }
}
