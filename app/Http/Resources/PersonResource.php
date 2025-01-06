<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
   
/**
 * @OA\Schema(
 *     schema="Person",
 *     title="person",
 *     description="Modelo de Persona",
 *     required={"id","typeofDocument", "documentNumber","state"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID de la persona"
 *     ),
 *     @OA\Property(
 *         property="typeofDocument",
 *         type="string",
 *         description="Tipo de documento de la persona"
 *     ),
 *     @OA\Property(
 *         property="documentNumber",
 *         type="string",
 *         description="Número de documento de la persona"
 *     ),
 *     @OA\Property(
 *         property="names",
 *         type="string",
 *         description="Nombres de la persona"
 *     ),
 *     @OA\Property(
 *         property="fatherSurname",
 *         type="string",
 *         description="Apellido paterno de la persona"
 *     ),
 *     @OA\Property(
 *         property="motherSurname",
 *         type="string",
 *         description="Apellido materno de la persona"
 *     ),
 *     @OA\Property(
 *         property="businessName",
 *         type="string",
 *         description="Nombre de la empresa de la persona"
 *     ),
 *      @OA\Property(
 *         property="representativePersonDni",
 *         type="string",
 *         description="DNI del representante de la persona"
 *     ),
 *     @OA\Property(
 *         property="representativePersonName",
 *         type="string",
 *         description="Nombre del representante de la persona"
 *     ),
 *
 *     @OA\Property(
 *         property="address",
 *         type="string",
 *         description="Dirección de la persona"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="Teléfono de la persona"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Correo electrónico de la persona"
 *     ),
 *     @OA\Property(
 *         property="origin",
 *         type="string",
 *         description="Lugar de origen de la persona"
 *     ),
 *     @OA\Property(
 *         property="ocupation",
 *         type="string",
 *         description="Ocupación de la persona"
 *     ),
 *     @OA\Property(
 *         property="state",
 *         type="boolean",
 *         description="Estado de la persona"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación de la persona"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de actualización de la persona"
 *     ),
 *     @OA\Property(
 *         property="deleted_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de eliminación de la persona"
 *     ),
 * )
 */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
