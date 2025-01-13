<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnvironmentRequest\IndexEnvironmentRequest;
use App\Http\Resources\EnvironmentResource;
use App\Models\Environment;
use App\Services\EnvironmentService;

class EnvironmentController extends Controller
{

    protected $environmentService;

    // Inyectamos el servicio en el controlador
    public function __construct(EnvironmentService $environmentService)
    {
        $this->environmentService = $environmentService;
    }

/**
 * @OA\Get(
 *     path="/reservas360-backend/public/api/environment",
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

    public function index(IndexEnvironmentRequest $request)
    {

        return $this->getFilteredResults(
            Environment::class,
            $request,
            Environment::filters,
            Environment::sorts,
            EnvironmentResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/environment/{id}",
     *     summary="Obtener detalles de un environment por ID",
     *     tags={"Environment"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la persona", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="persona encontrada", @OA\JsonContent(ref="#/components/schemas/Environment")),
     *     @OA\Response(response=404, description="Environment No Encontrado", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Environment No Encontrado")))
     * )
     */

    public function show($id)
    {

        $environment = $this->environmentService->getEnvironmentById($id);

        if (!$environment) {
            return response()->json([
                'error' => 'Environment No Encontrado',
            ], 404);
        }

        return new EnvironmentResource($environment);
    }

}
