<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
   /**
 * @OA\Schema(
 *     schema="Category",
 *     title="Category",
 *     description="Category model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sucursal 01"),
 *     @OA\Property(property="status", type="string", example="true"),
 * )
 */
public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'status' => $this->status,
    ];
}
}
