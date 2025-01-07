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
 *     @OA\Property(property="numberDocument", type="string", example="123456789"),
 *     @OA\Property(property="businessName", type="string", example="Mi Empresa S.A."),
 *     @OA\Property(property="address", type="string", example="Av. Siempre Viva 123"),
 *     @OA\Property(property="phone", type="string", example="999999999"),
 *     @OA\Property(property="email", type="string", format="email", example="empresa@ejemplo.com"),
 *     @OA\Property(property="state", type="string", example="active"),
 *     @OA\Property(property="server_id", type="string", example="server_001"),
 *     @OA\Property(property="branchoffices", type="array", @OA\Items(ref="#/components/schemas/BranchOffice"))
 * )
 */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'numberDocument' => $this->numberDocument,
            'businessName' => $this->businessName,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'branchoffices' => $this->branchoffices,
        ];
    }
}
