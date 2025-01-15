<?php
namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest\IndexCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\Api360Service;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $categoryService;
    protected $api360Service;

    // Inyectamos el servicio en el controlador
    public function __construct(CategoryService $categoryService, Api360Service $api360Service)
    {
        $this->categoryService = $categoryService;
        $this->api360Service   = $api360Service;

    }
/**
 * @OA\GET(
 *     path="/reservas360-backend/public/api/getdata-category",
 *     summary="Actualizar Categorías con datos externos",
 *     tags={"Api360"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(name="uuid", in="query", required=true, description="Identificador único", @OA\Schema(type="string", example="123e4567-e89b-12d3-a456-426614174000")),
 *     @OA\Response(response=200, description="Data actualizada", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="true"), @OA\Property(property="message", type="string", example="Data actualizada de Categorías"))),
 *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="false"), @OA\Property(property="message", type="string", example="Error al obtener datos")))
 * )
 */

    public function getCategories(Request $request)
    {
        $uuid = $request->input('uuid', '');
        $data = $this->api360Service->fetch_categories($uuid);

        return response()->json($data); // Devolvemos la respuesta
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/category",
     *     summary="Obtener información con filtros y ordenamiento",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="name", in="query", description="Filtrar por nombre de la categoria", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="branch$name", in="query", description="Filtrar por nombre de Sucursal", required=false, @OA\Schema(type="string")),
     *     @OA\Parameter(name="branch_id", in="query", description="Filtrar por id Sucursal", required=false, @OA\Schema(type="string")),

     *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200,description="Lista de Empresas",@OA\JsonContent(ref="#/components/schemas/Category")),
     *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
     * )
     */

    public function index(IndexCategoryRequest $request)
    {
        $respuesta_actualizar_data = $this->getCategories($request);
        return $this->getFilteredResults(
            Category::class,
            $request,
            Category::filters,
            Category::sorts,
            CategoryResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/category/{id}",
     *     summary="Obtener detalles de una categoria por ID",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la categoria", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="categoria encontrada", @OA\JsonContent(ref="#/components/schemas/Category")),
     *     @OA\Response(response=404, description="Categoria no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Categoria no encontrada")))
     * )
     */

    public function show($id)
    {
        $category = $this->categoryService->getCategoryById($id);

        if (! $category) {
            return response()->json([
                'error' => 'Categoria no encontrada',
            ], 404);
        }

        return new CategoryResource($category);
    }

}
