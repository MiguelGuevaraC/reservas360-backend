<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnvironmentResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="Environment",
     *     title="Environment",
     *     description="Environment model",
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Production"),
     *     @OA\Property(property="description", type="string", example="Primary production environment"),
     *     @OA\Property(property="route", type="string", example="/environment/production"),
     *     @OA\Property(property="status", type="boolean", example=true),
     *     @OA\Property(property="server_id", type="integer", example=10),
     *     @OA\Property(property="branch_id", type="integer", example=3),
     *     @OA\Property(property="stations", type="array", @OA\Items(ref="#/components/schemas/Station"))
     * )
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id ?? null,
            'name'        => $this->name ?? null,
            'description' => $this->description ?? null,
            'route'       => $this->route ?? null,
            'status'      => $this->status ?? null,
            'server_id'   => $this->server_id ?? null,
            'branch_id'   => $this->branch_id ?? null,
            'stations'    => $this->stations ? StationResource::collection($this->stations) : [],

        ];
    }

}
