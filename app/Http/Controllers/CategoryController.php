<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest\IndexCategoryRequest;
use App\Http\Requests\CategoryRequest\StoreCategoryRequest;
use App\Http\Requests\CategoryRequest\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services;


use App\Services\Api360Service;
use App\Services\CategoriesService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    protected $categoryService;
    protected $api360Service;

    // Inyectamos el servicio en el controlador
    public function __construct(CategoryService $categoryService,Api360Service $api360Service)
    {
        $this->categoryService = $categoryService;
        $this->api360Service = $api360Service;

    }

    /**
     * @OA\GET(
     *     path="/reservas360-backend/public/api/getdata-categories",
     *     summary="Actualizar Categorias la data de la api Externa",
     *     tags={"Api360"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(response=200, description="Data Actualizada de Categorias", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="true",property="message", type="string", example="Data Actualizada de Categorias"))),
     *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="status", type="string", example="false",property="message", type="string", example="Error al obtener datos de la API externa.")))
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

     *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200,description="Lista de Empresas",@OA\JsonContent(ref="#/components/schemas/Category")),
     *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
     * )
     */

    public function index(IndexCategoryRequest $request)
    {
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

        if (!$category) {
            return response()->json([
                'error' => 'Categoria no encontrada',
            ], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * @OA\Post(
     *     path="/reservas360-backend/public/api/category",
     *     summary="Crear una nueva categoria",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", required={"name", "numberDocument"}, @OA\Property(property="name", type="string", example="categoria Ejemplo"))),
     *     @OA\Response(response=200, description="categoria creada exitosamente", @OA\JsonContent(ref="#/components/schemas/Category")),
     *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
     * )
     */

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->validated());
        return new CategoryResource($category);
    }

    /**
     * @OA\Put(
     *     path="/reservas360-backend/public/api/category/{id}",
     *     summary="Actualizar la información de una categoria",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la categoria a actualizar", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", @OA\Property(property="name", type="string", example="categoria Ejemplo"))),
     *     @OA\Response(response=200, description="categoria actualizada exitosamente", @OA\JsonContent(ref="#/components/schemas/Category")),
     *     @OA\Response(response=404, description="Categoria no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Categoria no encontrada"))),
     *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
     * )
     */

    public function update(UpdateCategoryRequest $request, $id)
    {
        $validatedData = $request->validated();

        $category = $this->categoryService->getCategoryById($id);
        if (!$category) {
            return response()->json([
                'error' => 'Categoria no encontrada',
            ], 404);
        }

        $updatedCompany = $this->categoryService->updateCategory($category, $validatedData);
        return new CategoryResource($updatedCompany);
    }

    /**
     * @OA\Delete(
     *     path="/reservas360-backend/public/api/category/{id}",
     *     summary="Eliminar categoria por ID",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la categoria", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="categoria eliminada exitosamente", @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="categoria eliminada exitosamente"))),
     *     @OA\Response(response=404, description="Categoria no encontrada", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Categoria no encontrada")))
     * )
     */

    public function destroy($id)
    {
        $deleted = $this->categoryService->destroyById($id);

        if (!$deleted) {
            return response()->json([
                'error' => 'Categoria no encontrada o no se pudo eliminar',
            ], 404);
        }

        return response()->json([
            'message' => 'Categoria eliminada exitosamente',
        ], 200);
    }

}
