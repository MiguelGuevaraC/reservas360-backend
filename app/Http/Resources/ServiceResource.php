<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="Service",
     *     title="Service",
     *     description="Service model",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Sucursal 01"),
     *     @OA\Property(property="description", type="string", example="Sucursal 01"),
     *     @OA\Property(property="stock", type="string", example="Sucursal 01"),
     *     @OA\Property(property="price", type="string", example="Sucursal 01"),
     *
     *     @OA\Property(property="status", type="string", example="true"),
     *     @OA\Property(property="category_id", type="string", example="true"),
     * )
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, 
            'name' => $this->name ?? 'Sin Nombre',
            'description' => $this->description ?? 'Sin Descripción',
            'price' => $this->price ?? 'Sin Precio',
            'time_minutes' => $this->time_minutes ?? 'Sin Tiempo',
            'status' => $this->status ?? 'Sin Estado',

        ];
    }
}
