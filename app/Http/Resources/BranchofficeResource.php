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
 *     @OA\Property(property="name", type="string", example="Sucursal 01"),
 *     @OA\Property(property="address", type="string", example="AV. 123"),
 * )
 */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'company' => $this->company,
        ];
    }
}
