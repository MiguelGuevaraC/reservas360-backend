<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="Station",
     *     title="Station",
     *     description="Station model",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Station A"),
     *     @OA\Property(property="description", type="string", example="Monitoring"),
     *     @OA\Property(property="status", type="boolean", example=true),
     *     @OA\Property(property="server_id", type="integer", example=15),
     *     @OA\Property(property="route", type="string", example="/station/a"),
     *     @OA\Property(property="environment_id", type="integer", example=5),
     *     @OA\Property(property="environment", ref="#/components/schemas/Environment")
     * )
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id ?? null,
            'name'           => $this->name ?? null,
            'description'    => $this->description ?? null,
            'status'         => $this->status ?? null,
            'server_id'      => $this->server_id ?? null,
            'route'          => $this->route ?? null,
            'environment_id' => $this->environment_id ?? null,
        ];
    }

}
