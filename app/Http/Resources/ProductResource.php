<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
     /**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sucursal 01"),
 *     @OA\Property(property="description", type="string", example="Sucursal 01"),
 *     @OA\Property(property="photo", type="string", example="Sucursal 01"),
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
        'name' => $this->name,
        'description' => $this->description,
        'photo' => $this->photo,
        'stock' => $this->stock,
        'price' => $this->price,
        'status' => $this->status,
        'category' => $this->category ? new CategoryResource($this->category) : 'Sin Categoria',
    ];
}
}
