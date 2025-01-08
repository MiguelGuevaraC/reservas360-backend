<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest\IndexCompanyRequest;
use App\Http\Requests\CompanyRequest\StoreCompanyRequest;
use App\Http\Requests\CompanyRequest\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $companyService;

    // Inyectamos el servicio en el controlador
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @OA\GET(
     *     path="/reservas360-backend/public/api/getdata-company",
     *     summary="Actualizar información de empresas desde API externa",
     *     tags={"Api360"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Datos actualizados",
     *         @OA\JsonContent(example={"status": "true", "message": "Datos actualizados"})
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error al obtener datos",
     *         @OA\JsonContent(example={"status": "false", "message": "Error al obtener datos"})
     *     )
     * )
     */
    public function getCompanyData()
    {
        // Usamos el servicio para obtener la información de las empresas
        $data = $this->companyService->fetchCompanyData();

        return response()->json($data); // Devolvemos la respuesta
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/company",
     *     summary="Obtener información con filtros y ordenamiento",
     *     tags={"Company"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="numberDocument", in="query", description="Filtrar por número de documento", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="businessName", in="query", description="Filtrar por nombre del negocio", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="email", in="query", description="Filtrar por correo electrónico", required=false, @OA\Schema(type="string", format="email")),

     *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200,description="Lista de Empresas",@OA\JsonContent(ref="#/components/schemas/Company")),
     *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
     * )
     */

    public function index(IndexCompanyRequest $request)
    {
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

    /**
     * @OA\Post(
     *     path="/reservas360-backend/public/api/company",
     *     summary="Crear una nueva compañía",
     *     tags={"Company"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", required={"name", "numberDocument"}, @OA\Property(property="name", type="string", example="Compañía Ejemplo"), @OA\Property(property="numberDocument", type="string", example="123456789"), @OA\Property(property="businessName", type="string", example="Mi Empresa S.A."), @OA\Property(property="email", type="string", format="email", example="empresa@ejemplo.com"), @OA\Property(property="phone", type="string", example="999999999"))),
     *     @OA\Response(response=200, description="Compañía creada exitosamente", @OA\JsonContent(ref="#/components/schemas/Company")),
     *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
     * )
     */

    public function store(StoreCompanyRequest $request)
    {
        $company = $this->companyService->createCompany($request->validated());
        return new CompanyResource($company);
    }

/**
 * @OA\Put(
 *     path="/reservas360-backend/public/api/company/{id}",
 *     summary="Actualizar la información de una compañía",
 *     tags={"Company"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(name="id", in="path", description="ID de la compañía a actualizar", required=true, @OA\Schema(type="integer", example=1)),
 *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", @OA\Property(property="numberDocument", type="string", example="123456789"), @OA\Property(property="businessName", type="string", example="Mi Empresa Actualizada S.A."), @OA\Property(property="email", type="string", format="email", example="actualizado@example.com"))),
 *     @OA\Response(response=200, description="Compañía actualizada exitosamente", @OA\JsonContent(ref="#/components/schemas/Company")),
 *     @OA\Response(response=404, description="Compañía no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Compañía no encontrada"))),
 *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
 * )
 */

    public function update(UpdateCompanyRequest $request, $id)
    {
        $validatedData = $request->validated();

        $company = $this->companyService->getCompanyById($id);
        if (!$company) {
            return response()->json([
                'error' => 'Compañía no encontrada',
            ], 404);
        }

        $updatedCompany = $this->companyService->updateCompany($company, $validatedData);
        return new CompanyResource($updatedCompany);
    }

    /**
     * @OA\Delete(
     *     path="/reservas360-backend/public/api/company/{id}",
     *     summary="Eliminar compañía por ID",
     *     tags={"Company"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la compañía", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Compañía eliminada exitosamente", @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Compañía eliminada exitosamente"))),
     *     @OA\Response(response=404, description="Compañía no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Compañía no encontrada")))
     * )
     */

    public function destroy($id)
    {
        $deleted = $this->companyService->destroyById($id);

        if (!$deleted) {
            return response()->json([
                'error' => 'Compañía no encontrada o no se pudo eliminar',
            ], 404);
        }

        return response()->json([
            'message' => 'Compañía eliminada exitosamente',
        ], 200);
    }

}
