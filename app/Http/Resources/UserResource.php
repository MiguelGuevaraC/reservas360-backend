<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="User",
     *     title="User",
     *     description="User model",
     *     @OA\Property( property="id", type="integer", example="1" ),
     *     @OA\Property( property="email", type="string", example="miguel@gmail.com" ),

     *     @OA\Property(property="person_id",type="integer",description="Person Id", example="1"),
     *     @OA\Property(property="person", ref="#/components/schemas/Person")
     * )
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email ?? 'Sin Correo',
            'person' => $this?->person ?? 'Sin Persona',
        ];
    }
}
