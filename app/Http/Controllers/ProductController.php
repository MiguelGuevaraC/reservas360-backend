<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest\IndexProductRequest;
use App\Http\Requests\ProductRequest\StoreProductRequest;
use App\Http\Requests\ProductRequest\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\Api360Service;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $api360Service;

    // Inyectamos el servicio en el controlador
    public function __construct(ProductService $productService, Api360Service $api360Service)
    {
        $this->productService = $productService;
        $this->api360Service = $api360Service;

    }

    public function getProducts(Request $request)
    {

        $uuid = $request->input('uuid', '');

        $data[] = $this->api360Service->fetch_categories($uuid);
        $data[] = $this->api360Service->fetch_products($uuid);

        return response()->json($data); // Devolvemos la respuesta
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/product",
     *     summary="Obtener información con filtros y ordenamiento",
     *     tags={"Product"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="name", in="query", description="Filtrar por nombre de la categoria", required=false, @OA\Schema(type="string")),

     *     @OA\Parameter(name="from", in="query", description="Fecha de inicio", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="to", in="query", description="Fecha de fin", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Response(response=200,description="Lista de Empresas",@OA\JsonContent(ref="#/components/schemas/Product")),
     *     @OA\Response(response=422, description="Validación fallida", @OA\JsonContent(type="object", @OA\Property(property="error", type="string")))
     * )
     */

    public function index(IndexProductRequest $request)
    {
        $respuesta_actualizar_data= $this->getProducts($request);
        return $this->getFilteredResults(
            Product::class,
            $request,
            Product::filters,
            Product::sorts,
            ProductResource::class
        );
    }

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/product/{id}",
     *     summary="Obtener detalles de una categoria por ID",
     *     tags={"Product"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", description="ID de la categoria", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="categoria encontrado", @OA\JsonContent(ref="#/components/schemas/Product")),
     *     @OA\Response(response=404, description="Producto no encontrado", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Producto no encontrado")))
     * )
     */

    public function show($id)
    {
        $category = $this->productService->getProductById($id);

        if (!$category) {
            return response()->json([
                'error' => 'Producto no encontrado',
            ], 404);
        }

        return new ProductResource($category);
    }

    // /**
    //  * @OA\Post(
    //  *     path="/reservas360-backend/public/api/product",
    //  *     summary="Crear una nueva categoria",
    //  *     tags={"Product"},
    //  *     security={{"bearerAuth": {}}},
    //  *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", required={"name", "numberDocument"}, @OA\Property(property="name", type="string", example="categoria Ejemplo"))),
    //  *     @OA\Response(response=200, description="categoria creada exitosamente", @OA\JsonContent(ref="#/components/schemas/Product")),
    //  *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
    //  * )
    //  */

    // public function store(StoreProductRequest $request)
    // {
    //     $category = $this->productService->createProduct($request->validated());
    //     return new ProductResource($category);
    // }

    // /**
    //  * @OA\Put(
    //  *     path="/reservas360-backend/public/api/product/{id}",
    //  *     summary="Actualizar la información de una categoria",
    //  *     tags={"Product"},
    //  *     security={{"bearerAuth": {}}},
    //  *     @OA\Parameter(name="id", in="path", description="ID de la categoria a actualizar", required=true, @OA\Schema(type="integer", example=1)),
    //  *     @OA\RequestBody(required=true, @OA\JsonContent(type="object", @OA\Property(property="name", type="string", example="categoria Ejemplo"))),
    //  *     @OA\Response(response=200, description="categoria actualizada exitosamente", @OA\JsonContent(ref="#/components/schemas/Product")),
    //  *     @OA\Response(response=404, description="Producto no encontrado", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Producto no encontrado"))),
    //  *     @OA\Response(response=422, description="Error de validación", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Datos inválidos")))
    //  * )
    //  */

    // public function update(UpdateProductRequest $request, $id)
    // {
    //     $validatedData = $request->validated();

    //     $category = $this->productService->getProductById($id);
    //     if (!$category) {
    //         return response()->json([
    //             'error' => 'Producto no encontrado',
    //         ], 404);
    //     }

    //     $updatedCompany = $this->productService->updateProduct($category, $validatedData);
    //     return new ProductResource($updatedCompany);
    // }

    // /**
    //  * @OA\Delete(
    //  *     path="/reservas360-backend/public/api/product/{id}",
    //  *     summary="Eliminar categoria por ID",
    //  *     tags={"Product"},
    //  *     security={{"bearerAuth": {}}},
    //  *     @OA\Parameter(name="id", in="path", description="ID de la categoria", required=true, @OA\Schema(type="integer", example=1)),
    //  *     @OA\Response(response=200, description="categoria eliminada exitosamente", @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="categoria eliminada exitosamente"))),
    //  *     @OA\Response(response=404, description="Producto no encontrado", @OA\JsonContent(type="object", @OA\Property(property="error", type="string", example="Producto no encontrado")))
    //  * )
    //  */

    // public function destroy($id)
    // {
    //     $deleted = $this->productService->destroyById($id);

    //     if (!$deleted) {
    //         return response()->json([
    //             'error' => 'Producto no encontrado o no se pudo eliminar',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'message' => 'Producto eliminada exitosamente',
    //     ], 200);
    // }
}
