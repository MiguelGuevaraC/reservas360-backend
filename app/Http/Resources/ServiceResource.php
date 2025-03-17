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
            'id'           => $this->id ?? null,
            'name'         => $this->name ?? null,
            'description'  => $this->description ?? null,
            'price'        => $this->price ?? null,
            'time_minutes' => $this->time_minutes ?? null,
            'status'       => $this->status ?? null,
            'category_id'  => $this->category_id ?? null,
            'category_name'  => $this?->category?->name ?? null,
        ];
    }
}
