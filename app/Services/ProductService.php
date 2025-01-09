<?php
namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductService
{

    public function getProductById(int $id): ?Product
    {
        return Product::find($id);
    }
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct($Product, array $data)
    {
        $Product->update($data);
        return $Product;
    }
    public function destroyById($id)
    {
        $Product = Product::find($id);

        if (!$Product) {
            return false;
        }
        return $Product->delete(); // Devuelve true si la eliminación fue exitosa
    }

    // El método recibe el UUID de la sucursal
    public function fetchProduct(Request $request)
    {
        try {
            // Realizar la solicitud GET con los headers necesarios
            $response = Http::withHeaders([
                'Authorization' => '7554dbfe-74ea-4dfb-b997-47633f9b5761', // Token de autorización
            ])->get('https://sistema.360sys.com.pe/api/app-mobile/products-and-services');

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                // Obtener los datos de la respuesta
                $data = $response->json();

                // Suponiendo que la respuesta contiene un array en 'data.products'
                $branch_info_data = $data['data']['products'] ?? [];

                if (!empty($branch_info_data)) {
                    foreach ($branch_info_data as $Product) {
                        // Verificar si la producto ya existe en la base de datos utilizando el campo 'id' del servidor
                        $existingProduct = Product::where('server_id', $Product['id'])->first();
                        $existingCategory = Category::where('server_id', $Product['category_id'] ?? null)->first();

                        if (!$existingProduct) {
                            // Si no existe, crear una nueva producto con los datos obtenidos
                            $newProduct = Product::create([
                                'name' => $Product['name'] ?? '',
                                'description' => $Product['description'] ?? '',
                                'photo' => $Product['photo'] ?? '',
                                'stock' => $Product['stock'] ?? '',
                                'price' => $Product['price'] ?? '',
                                'category_id' => $existingCategory->id ?? '',
                                'status' => $Product['status'] ?? '',
                                'server_id' => $Product['id'] ?? '',
                            ]);

                            // Usamos el recurso para devolver la respuesta (opcional, solo si necesitas procesar cada elemento)
                            new ProductResource($newProduct);
                        } else {
                            // Si existe, actualizar la producto con los nuevos datos
                            $existingProduct->update([
                                'name' => $Product['name'] ?? $existingProduct->name,
                                'description' => $Product['description'] ?? $existingProduct->description,
                                'photo' => $Product['photo'] ?? $existingProduct->photo,
                                'stock' => $Product['stock'] ?? $existingProduct->stock,
                                'price' => $Product['price'] ?? $existingProduct->price,
                                'category_id' => $existingCategory->id ?? $existingProduct->category_id,

                                'status' => $Product['status'] ?? $existingProduct->status,
                                'server_id' => $Product['id'] ?? $existingProduct->server_id,
                            ]);

                            // Usamos el recurso para devolver la respuesta (opcional, solo si necesitas procesar cada elemento)
                            new ProductResource($existingProduct);
                        }
                    }

                    return ([
                        'status' => true,
                        'message' => 'Datos de productos actualizados correctamente.',
                    ]);
                } else {
                    return ([
                        'status' => false,
                        'message' => 'No se encontró información de productos en la respuesta.',
                    ]);
                }
            } else {
                return ([
                    'status' => false,
                    'message' => 'La solicitud no fue exitosa.',
                ]);
            }

        } catch (\Exception $e) {
            // Manejo de cualquier excepción
            return ([
                'status' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ]);
        }
    }

    public function fetchDataAndSync(
        string $endpoint, // Nombre de la ruta a solicitar
        string $dataKey, // Nombre de la key para obtener la data
        string $modelClass, // Nombre del modelo
        array $fields, // Campos del modelo a sincronizar
        string $authorizationUiid // Token de autorización
    ) {
        try {
            $endpoint = "https://sistema.360sys.com.pe/api/app-mobile/" . $endpoint;
    
            $response = Http::withHeaders([
                'Authorization' => $authorizationUiid,
            ])->get($endpoint);
    
            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $data = $response->json();
                $items = $data['data'][$dataKey] ?? []; // Obtener los datos dinámicamente
    
                if (!empty($items)) {
                    foreach ($items as $item) {
                        // Crear o actualizar el modelo según el campo 'server_id'
                        $modelClass::updateOrCreate(
                            ['server_id' => $item['id']], // Buscar por el campo 'server_id'
                            array_merge(
                                array_intersect_key($item, array_flip($fields)), // Solo sincronizar los campos requeridos
                                ['server_id' => $item['id']] // Asignar el 'server_id'
                            )
                        );
                    }
    
                    return [
                        'status' => true,
                        'message' => "Datos sincronizados correctamente para el modelo {$modelClass}.",
                    ];
                } else {
                    return [
                        'status' => false,
                        'message' => "No se encontraron datos en la clave '{$dataKey}'.",
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'message' => 'La solicitud a la API no fue exitosa.',
                ];
            }
        } catch (\Exception $e) {
            // Manejo de excepciones
            return [
                'status' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ];
        }
    }
    

}
