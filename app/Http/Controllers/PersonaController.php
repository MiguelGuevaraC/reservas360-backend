<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest\IndexPersonRequest;
use App\Http\Resources\PersonResource;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonaController extends Controller
{

/**
 * @OA\Get(
 *     path="/reservas360-backend/public/api/person",
 *     summary="Obtener información con filtros y ordenamiento",
 *     tags={"Person"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(name="names", in="query", description="Filtrar por nombre", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="typeofDocument", in="query", description="Filtrar por tipo de documento", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="fathersurname", in="query", description="Filtrar por apellido paterno", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="mothersurname", in="query", description="Filtrar por apellido materno", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="documentNumber", in="query", description="Filtrar por número de documento", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="businessName", in="query", description="Filtrar por nombre del negocio", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="email", in="query", description="Filtrar por correo electrónico", required=false, @OA\Schema(type="string", format="email")),
 *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Response(response=200, description="Respuesta exitosa", @OA\JsonContent(type="object")),
 *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
 * )
 */

    public function index(IndexPersonRequest $request)
    {
        return $this->getFilteredResults(
            Person::class,
            $request,
            Person::filters,
            Person::sorts,
            PersonResource::class
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
