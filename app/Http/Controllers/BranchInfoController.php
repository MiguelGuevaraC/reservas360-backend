<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchOfficeRequest\IndexBranchOfficeRequest;
use App\Http\Requests\BranchOfficeRequest\StoreBranchOfficeRequest;
use App\Http\Requests\BranchOfficeRequest\UpdateBranchOfficeRequest;
use App\Http\Resources\BranchofficeResource;
use App\Models\Branchoffice;
use App\Services\Api360Service;
use App\Services\BranchInfoService;
use App\Services\CompanyService;
use Illuminate\Http\Request;

class BranchInfoController extends Controller
{
   
    protected $api360Service;
    protected $branchInfoService;

    // Inyectamos el servicio en el controlador
    public function __construct(BranchInfoService $branchInfoService,Api360Service $api360Service)
    {
        $this->branchInfoService = $branchInfoService;
        $this->api360Service = $api360Service;
    }

    /**
     * @OA\GET(
     *     path="/reservas360-backend/public/api/getdata-branchoffice",
     *     summary="Actualizar Sucursales la data de la api Externa",
  *     tags={"Api360"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(name="uuid", in="query", required=true, description="Identificador único", @OA\Schema(type="string", example="123e4567-e89b-12d3-a456-426614174000")),
 *     @OA\Response(response=200, description="Data actualizada", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="true"), @OA\Property(property="message", type="string", example="Data actualizada de Categorías"))),
 *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="false"), @OA\Property(property="message", type="string", example="Error al obtener datos")))
 * )
 */
    public function getBranchInfo(Request $request)
    {
        $uuid = $request->input('uuid', '');
        // Usamos el servicio para obtener la información de la sucursal
        $this->api360Service->fetch_compannies($uuid);
        $data = $this->api360Service->fetch_branches();

        return response()->json($data); // Devolvemos la respuesta
    }


    /**
 * @OA\Get(
 *     path="/reservas360-backend/public/api/branchoffice",
 *     summary="Obtener información con filtros y ordenamiento",
 *     tags={"BranchOffice"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(name="uuid", in="query", description="Filtrar por UUID", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="brand_name", in="query", description="Filtrar por nombre de la marca", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="ruc", in="query", description="Filtrar por RUC", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="name", in="query", description="Filtrar por nombre de la sucursal", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="address", in="query", description="Filtrar por dirección del negocio", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="phone", in="query", description="Filtrar por teléfono", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="telephone", in="query", description="Filtrar por teléfono fijo", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="email", in="query", description="Filtrar por correo electrónico", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="company$businessName", in="query", description="Filtrar por razón social de la empresa", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
 *     @OA\Response(response=200, description="Lista de Sucursales", @OA\JsonContent(ref="#/components/schemas/BranchOffice")),
 *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
 * )
 */


     public function index(IndexBranchOfficeRequest $request)
     {
        $respuesta_actualizar_data= $this->getBranchInfo($request);
         return $this->getFilteredResults(
             Branchoffice::class,
             $request,
             Branchoffice::filters,
             Branchoffice::sorts,
             BranchofficeResource::class
         );
     }
 
     /**
      * @OA\Get(
      *     path="/reservas360-backend/public/api/branchoffice/{id}",
      *     summary="Obtener detalles de una sucursal por ID",
      *     tags={"BranchOffice"},
      *     security={{"bearerAuth": {}}},
      *     @OA\Parameter(name="id", in="path", description="ID de la sucursal", required=true, @OA\Schema(type="integer", example=1)),
      *     @OA\Response(response=200, description="sucursal encontrada", @OA\JsonContent(ref="#/components/schemas/BranchOffice")),
      *     @OA\Response(response=404, description="sucursal no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="sucursal no encontrada")))
      * )
      */
 
     public function show($id)
     {
         $company = $this->branchInfoService->getBranchOfficeById($id);
 
         if (!$company) {
             return response()->json([
                 'error' => 'sucursal no encontrada',
             ], 404);
         }
 
         return new BranchofficeResource($company);
     }
 
//      /**
//       * @OA\Post(
//       *     path="/reservas360-backend/public/api/branchoffice",
//       *     summary="Crear una nueva sucursal",
//       *     tags={"BranchOffice"},
//       *     security={{"bearerAuth": {}}},
//       *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", required={"name", "numberDocument"}, @OA\Property(property="name", type="string", example="sucursal Ejemplo"), @OA\Property(property="address", type="string", example="123456789"),  @OA\Property(property="company_id", type="integer", example="1"))),
//       *     @OA\Response(response=200, description="sucursal creada exitosamente", @OA\JsonContent(ref="#/components/schemas/BranchOffice")),
//       *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
//       * )
//       */
 
//      public function store(StoreBranchOfficeRequest $request)
//      {
//          $company = $this->branchInfoService->createBranchOffice($request->validated());
//          return new BranchofficeResource($company);
//      }
 
//  /**
//   * @OA\Put(
//   *     path="/reservas360-backend/public/api/branchoffice/{id}",
//   *     summary="Actualizar la información de una sucursal",
//   *     tags={"BranchOffice"},
//   *     security={{"bearerAuth": {}}},
//   *     @OA\Parameter(name="id", in="path", description="ID de la sucursal a actualizar", required=true, @OA\Schema(type="integer", example=1)),
//   *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", @OA\Property(property="name", type="string", example="sucursal Ejemplo"), @OA\Property(property="address", type="string", example="123456789"),  @OA\Property(property="company_id", type="integer", example="1"))),
//   *     @OA\Response(response=200, description="sucursal actualizada exitosamente", @OA\JsonContent(ref="#/components/schemas/BranchOffice")),
//   *     @OA\Response(response=404, description="sucursal no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="sucursal no encontrada"))),
//   *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
//   * )
//   */
 
//      public function update(UpdateBranchOfficeRequest $request, $id)
//      {
//          $validatedData = $request->validated();
 
//          $company = $this->branchInfoService->getBranchOfficeById($id);
//          if (!$company) {
//              return response()->json([
//                  'error' => 'sucursal no encontrada',
//              ], 404);
//          }
 
//          $updatedCompany = $this->branchInfoService->updateBranchOffice($company, $validatedData);
//          return new BranchofficeResource($updatedCompany);
//      }
 
//      /**
//       * @OA\Delete(
//       *     path="/reservas360-backend/public/api/branchoffice/{id}",
//       *     summary="Eliminar sucursal por ID",
//       *     tags={"BranchOffice"},
//       *     security={{"bearerAuth": {}}},
//       *     @OA\Parameter(name="id", in="path", description="ID de la sucursal", required=true, @OA\Schema(type="integer", example=1)),
//       *     @OA\Response(response=200, description="sucursal eliminada exitosamente", @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="sucursal eliminada exitosamente"))),
//       *     @OA\Response(response=404, description="sucursal no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="sucursal no encontrada")))
//       * )
//       */
 
//      public function destroy($id)
//      {
//          $deleted = $this->branchInfoService->destroyById($id);
 
//          if (!$deleted) {
//              return response()->json([
//                  'error' => 'sucursal no encontrada o no se pudo eliminar',
//              ], 404);
//          }
 
//          return response()->json([
//              'message' => 'sucursal eliminada exitosamente',
//          ], 200);
//      }

}
