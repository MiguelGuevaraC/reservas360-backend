<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchofficeResource extends JsonResource
{
/**
 * @OA\Schema(
 *     schema="BranchOffice",
 *     title="BranchOffice",
 *     description="Branch Office model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="brand_name", type="string", example="Marca XYZ"),
 *     @OA\Property(property="ruc", type="string", example="12345678901"),
 *     @OA\Property(property="name", type="string", example="Sucursal 01"),
 *     @OA\Property(property="address", type="string", example="AV. 123"),
 *     @OA\Property(property="phone", type="string", example="987654321"),
 *     @OA\Property(property="telephone", type="string", example="654321987"),
 *     @OA\Property(property="email", type="string", example="example@domain.com"),
 *     @OA\Property(property="server_id", type="integer", example=10),
 *     @OA\Property(property="company_id", type="integer", example=5),
 * 
 *     @OA\Property(property="branchoffices", type="array", @OA\Items(ref="#/components/schemas/BranchOffice")),
 *     @OA\Property(property="products", type="array", @OA\Items(ref="#/components/schemas/Product")),
 *     @OA\Property(property="services", type="array", @OA\Items(ref="#/components/schemas/Service"))
 * )
 */
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? '',
            'brand_name' => $this->brand_name ?? 'Sin Marca',
            'ruc' => $this->ruc ?? 'Sin Ruc',
            'name' => $this->name ?? 'Sin Nombre',
            'address' => $this->address ?? 'Sin Dirección',
            'phone' => $this->phone ?? 'Sin Telefono',
            'telephone' => $this->telephone ?? 'Sin Telefono',
            'email' => $this->email ?? 'Sin Correo',
            'server_id' => $this->server_id,
            'company_id' => $this->company_id,

            'products' =>  $this->products ? ProductResource::collection($this->products) : [],
            'services' =>  $this->services ? ServiceResource::collection($this->services) : [],
            'environments' =>  $this->environments ? EnvironmentResource::collection($this->environments) : [],
      
        
        ];
    }

}
