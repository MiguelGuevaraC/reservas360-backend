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
     *     @OA\Property(property="branch_id", type="string", example="1"),
     *     @OA\Property(property="products", type="array", @OA\Items(ref="#/components/schemas/Product")),
     *     @OA\Property(property="services", type="array", @OA\Items(ref="#/components/schemas/Service")),
     * )
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id ?? null,
            'name'      => $this->name ?? null,
            'status'    => $this->status ?? null,
            'branch_id' => $this->branch_id ?? null,

            'products'  => $this->products ? ProductResource::collection($this->products) : [],
            'services'  => $this->services ? ServiceResource::collection($this->services) : [],
        ];
    }
}
