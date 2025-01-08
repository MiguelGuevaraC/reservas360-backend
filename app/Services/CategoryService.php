<?php
namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

class CategoryService
{

    public function getCategoryById(int $id): ?Category
    {
        return Category::find($id);
    }
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory($category, array $data)
    {
        $category->update($data);
        return $category;
    }
    public function destroyById($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return false;
        }
        return $category->delete(); // Devuelve true si la eliminación fue exitosa
    }

    // El método recibe el UUID de la sucursal
    public function fetchCategory()
    {
        try {
            // Realizar la solicitud GET con los headers necesarios
            $response = Http::withHeaders([
                'Authorization' => '7554dbfe-74ea-4dfb-b997-47633f9b5761', // Token de autorización
            ])->get('https://sistema.360sys.com.pe/api/app-mobile/categories');

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                // Obtener los datos de la respuesta
                $data = $response->json();
            
                // Suponiendo que la respuesta contiene un array en 'data.categories'
                $branch_info_data = $data['data']['categories'] ?? [];
            
                if (!empty($branch_info_data)) {
                    foreach ($branch_info_data as $category) {
                        // Verificar si la categoría ya existe en la base de datos utilizando el campo 'id' del servidor
                        $existingCategory = Category::where('server_id', $category['id'])->first();
            
                        if (!$existingCategory) {
                            // Si no existe, crear una nueva categoría con los datos obtenidos
                            $newCategory = Category::create([
                                'name' => $category['name'] ?? '',
                                'status' => $category['status'] ?? '',
                                'server_id' => $category['id'] ?? '',
                            ]);
            
                            // Usamos el recurso para devolver la respuesta (opcional, solo si necesitas procesar cada elemento)
                            new CategoryResource($newCategory);
                        } else {
                            // Si existe, actualizar la categoría con los nuevos datos
                            $existingCategory->update([
                                'name' => $category['name'] ?? $existingCategory->name,
                                'status' => $category['status'] ?? $existingCategory->status,
                                'server_id' => $category['id'] ?? $existingCategory->server_id,
                            ]);
            
                            // Usamos el recurso para devolver la respuesta (opcional, solo si necesitas procesar cada elemento)
                            new CategoryResource($existingCategory);
                        }
                    }
            
                    return ([
                        'status' => true,
                        'message' => 'Datos de categorías actualizados correctamente.',
                    ]);
                } else {
                    return ([
                        'status' => false,
                        'message' => 'No se encontró información de categorías en la respuesta.',
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
}
