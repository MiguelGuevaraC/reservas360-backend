<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StationRequest\IndexStationRequest;
use App\Http\Requests\StationRequest\StoreStationRequest;
use App\Http\Requests\StationRequest\UpdateStationRequest;
use App\Http\Resources\StationResource;
use App\Models\Station;
use App\Services\StationService;

class StationController extends Controller
{
    protected $stationService;


    // Inyectamos el servicio en el controlador
    public function __construct(StationService $stationService)
    {
        $this->stationService = $stationService;
    }
 
 /**
 * @OA\Get(
 *     path="/gia-backend/public/api/station",
 *     summary="Obtener información con filtros y ordenamiento",
 *     tags={"Environment"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(name="name", in="query", description="Filtrar por nombre", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="description", in="query", description="Filtrar por descripción", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="route", in="query", description="Filtrar por ruta", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", description="Filtrar por estado", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="company$business_name", in="query", description="Filtrar por nombre de empresa", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="company_id", in="query", description="Filtrar por id Empresa", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Response(response=200, description="Lista de Entornos", @OA\JsonContent(ref="#/components/schemas/Environment")),
 *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(@OA\Property(property="error", type="string")))
 * )
 */

    public function index(IndexStationRequest $request)
    {

        return $this->getFilteredResults(
            Station::class,
            $request,
            Station::filters,
            Station::sorts,
            StationResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/station/{id}",
     *     summary="Obtener detalles de un station por ID",
     *     tags={"Station"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la persona", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="persona encontrada", @OA\JsonContent(ref="#/components/schemas/Station")),
     *     @OA\Response(response=404, description="Station No Encontrado", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Station No Encontrado")))
     * )
     */

    public function show($id)
    {

        $station = $this->stationService->getStationById($id);

        if (!$station) {
            return response()->json([
                'error' => 'Station No Encontrado',
            ], 404);
        }

        return new StationResource($station);
    }

   
}
