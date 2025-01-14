<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest\IndexCompanyRequest;
use App\Http\Requests\CompanyRequest\StoreCompanyRequest;
use App\Http\Requests\CompanyRequest\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\Api360Service;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $api360Service;
    protected $companyService;

    // Inyectamos el servicio en el controlador
    public function __construct(Api360Service $api360Service, CompanyService $companyService)
    {
        $this->api360Service = $api360Service;
        $this->companyService = $companyService;
    }

    /**
     * @OA\GET(
     *     path="/reservas360-backend/public/api/getdata-company",
     *     summary="Actualizar información de empresas desde API externa",
 *     tags={"Api360"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(name="uuid", in="query", required=true, description="Identificador único", @OA\Schema(type="string", example="123e4567-e89b-12d3-a456-426614174000")),
 *     @OA\Response(response=200, description="Data actualizada", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="true"), @OA\Property(property="message", type="string", example="Data actualizada de Categorías"))),
 *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="false"), @OA\Property(property="message", type="string", example="Error al obtener datos")))
 * )
 */
    public function getCompanyData(Request $request)
    {
        $uuid = $request->input('uuid', '');
        $data = $this->api360Service->fetch_compannies($uuid);
        return response()->json($data); // Devolvemos la respuesta
    }

  /**
 * @OA\Get(
 *     path="/reservas360-backend/public/api/company",
 *     summary="Obtener información con filtros y ordenamiento",
 *     tags={"Company"},
 *     security={{"bearerAuth": {}}},
 *     
 *     @OA\Parameter(name="ruc", in="query", description="Filtrar por RUC", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="name", in="query", description="Filtrar por nombre", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="address", in="query", description="Filtrar por dirección", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="phone", in="query", description="Filtrar por teléfono", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="telephone", in="query", description="Filtrar por teléfono fijo", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="email", in="query", description="Filtrar por correo electrónico", required=false, @OA\Schema(type="string", format="email")),
 
 *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
 *     
 *     @OA\Response(response=200, description="Lista de Empresas", @OA\JsonContent(ref="#/components/schemas/Company")),
 *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
 * )
 */


    public function index(IndexCompanyRequest $request)
    {
        $respuesta_actualizar_data= $this->getCompanyData($request);
        return $this->getFilteredResults(
            Company::class,
            $request,
            Company::filters,
            Company::sorts,
            CompanyResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/company/{id}",
     *     summary="Obtener detalles de una compañía por ID",
     *     tags={"Company"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la compañía", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Compañía encontrada", @OA\JsonContent(ref="#/components/schemas/Company")),
     *     @OA\Response(response=404, description="Compañía no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Compañía no encontrada")))
     * )
     */

    public function show($id)
    {
        $company = $this->companyService->getCompanyById($id);

        if (!$company) {
            return response()->json([
                'error' => 'Compañía no encontrada',
            ], 404);
        }

        return new CompanyResource($company);
    }


}
