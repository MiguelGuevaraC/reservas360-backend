<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest\IndexServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\Api360Service;
use App\Services\ServiceService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $serviceService;
    protected $api360Service;

    // Inyectamos el servicio en el controlador
    public function __construct(ServiceService $serviceService, Api360Service $api360Service)
    {
        $this->serviceService = $serviceService;
        $this->api360Service = $api360Service;

    }

    /**
     * @OA\GET(
     *     path="/reservas360-backend/public/api/getdata-service",
     *     summary="Actualizar Categorias la data de la api Externa",
     *     tags={"Api360"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="uuid", in="query", required=true, description="Identificador único", @OA\Schema(type="string", example="123e4567-e89b-12d3-a456-426614174000")),
     *     @OA\Response(response=200, description="Data actualizada", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="true"), @OA\Property(property="message", type="string", example="Data actualizada de Categorías"))),
     *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="false"), @OA\Property(property="message", type="string", example="Error al obtener datos")))
     * )
     */
    public function getServiceData(Request $request)
    {
        $uuid = $request->input('uuid', '');

        $data = $this->api360Service->fetch_services($uuid);

        return response()->json($data); // Devolvemos la respuesta
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/service",
     *     summary="Obtener información con filtros y ordenamiento",
     *     tags={"Service"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="name", in="query", description="Filtrar por nombre de la categoría", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="description", in="query", description="Filtrar por descripción de la categoría", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="price", in="query", description="Filtrar por precio", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="time_minutes", in="query", description="Filtrar por tiempo en minutos", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", description="Filtrar por estado (activo/inactivo)", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="category_id", in="query", description="Filtrar por ID de la categoría", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="category&name", in="query", description="Filtrar por nombre de la categoría asociada", required=false, @OA\Schema(type="string")),


     *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200,description="Lista de Empresas",@OA\JsonContent(ref="#/components/schemas/Service")),
     *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
     * )
     */

    public function index(IndexServiceRequest $request)
    {
        $respuesta_actualizar_data = $this->getCategories($request);
        return $this->getFilteredResults(
            Service::class,
            $request,
            Service::filters,
            Service::sorts,
            ServiceResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/service/{id}",
     *     summary="Obtener detalles de una categoria por ID",
     *     tags={"Service"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la categoria", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="categoria encontrada", @OA\JsonContent(ref="#/components/schemas/Service")),
     *     @OA\Response(response=404, description="Categoria no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Categoria no encontrada")))
     * )
     */

    public function show($id)
    {
        $service = $this->serviceService->getServiceById($id);

        if (!$service) {
            return response()->json([
                'error' => 'Categoria no encontrada',
            ], 404);
        }

        return new ServiceResource($service);
    }
}
