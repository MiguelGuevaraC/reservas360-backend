<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{

/**
 * @OA\Schema(
 *     schema="Company",
 *     title="Company",
 *     description="Company model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="ruc", type="string", example="123456789"),
 *     @OA\Property(property="name", type="string", example="Mi Empresa S.A."),
 *     @OA\Property(property="address", type="string", example="Av. Siempre Viva 123"),
 *     @OA\Property(property="phone", type="string", example="999999999"),
 *     @OA\Property(property="telephone", type="string", example="987654321"),
 *     @OA\Property(property="email", type="string", format="email", example="empresa@ejemplo.com"),
 *     @OA\Property(property="state", type="string", example="active"),
 *     @OA\Property(property="server_id", type="integer", example=1),
 *     @OA\Property(property="branchoffices", type="array", @OA\Items(ref="#/components/schemas/BranchOffice"))
 * )
 */
    public function toArray($request)
    {
        return [
            'id'            => $this->id ?? null,
            'ruc'           => $this->ruc ?? null,
            'name'          => $this->name ?? null,
            'address'       => $this->address ?? null,
            'phone'         => $this->phone ?? null,
            'telephone'     => $this->telephone ?? null,
            'email'         => $this->email ?? null,
            'status'        => $this->status ?? null,
            'server_id'     => $this->server_id ?? null,
            'branchoffices' => $this->branchoffices ? BranchOfficeResource::collection($this->branchoffices) : [],

        ];
    }

}
