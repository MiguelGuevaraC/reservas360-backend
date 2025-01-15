<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnvironmentRequest\IndexEnvironmentRequest;
use App\Http\Resources\EnvironmentResource;
use App\Models\Environment;
use App\Services\EnvironmentService;
use Illuminate\Http\Request;

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
 *     @OA\Parameter(name="branch$name", in="query", description="Filtrar por nombre de Sucursal", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="branch_id", in="query", description="Filtrar por id Sucursal", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Response(response=200, description="Lista de Entornos", @OA\JsonContent(ref="#/components/schemas/Environment")),
 *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(@OA\Property(property="error", type="string")))
 * )
 */

    public function index(IndexEnvironmentRequest $request)
    {
        $respuesta_actualizar_data = $this->getEnvironmentData($request);
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

    /**
     * @OA\GET(
     *     path="/reservas360-backend/public/api/getdata-environment",
     *     summary="Actualizar información de Ambientes desde API externa",
     *     tags={"Api360"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="uuid", in="query", required=true, description="Identificador único", @OA\Schema(type="string", example="123e4567-e89b-12d3-a456-426614174000")),
     *     @OA\Response(response=200, description="Data actualizada", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="true"), @OA\Property(property="message", type="string", example="Data actualizada de Categorías"))),
     *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="false"), @OA\Property(property="message", type="string", example="Error al obtener datos")))
     * )
     */

     
    public function getEnvironmentData(Request $request)
    {
        $uuid = $request->input('uuid', '');
        $data = '';
        // $data = $this->environmentService->fetch_environments($uuid);
        return response()->json($data); // Devolvemos la respuesta
    }
}
