<?php
namespace App\Services;

use App\Models\Branchoffice;
use App\Models\Category;
use App\Models\Company;
use App\Models\Environment;
use App\Models\Product;
use App\Models\Service;
use App\Models\Station;
use Illuminate\Support\Facades\Http;

class Api360Service
{

    public function fetch_compannies(?string $uuid = '')
    {

        return $this->fetchDataAndSync(
            'branch-info',
            'company',
            Company::class,
            Company::getfields,
            $uuid,
            []// Relación dinámica
        );
    }

    public function fetch_branches(?string $uuid = '')
    {

        return $this->fetchDataAndSync(
            'branch-info',
            'branch',
            Branchoffice::class,
            Branchoffice::getfields,
            $uuid,
            ['company_id' => Company::class]// Relación dinámica
        );
    }

    public function fetch_categories(?string $uuid = '')
    {

        return $this->fetchDataAndSync(
            'categories',
            'categories',
            Category::class,
            Category::getfields,
            $uuid,
            ['category_id' => Category::class]// Relación dinámica
        );
    }

    public function fetch_products(?string $uuid = '')
    {
        return $this->fetchDataAndSync(
            'products-and-services',
            'products',
            Product::class,
            Product::getfields,
            $uuid,
            ['category_id' => Category::class]// Relación dinámica
        );
    }

    public function fetch_services(?string $uuid = '')
    {
        return $this->fetchDataAndSync(
            'products-and-services',
            'services',
            Service::class,
            Service::getfields,
            $uuid,
            ['category_id' => Category::class]// Relación dinámica
        );
    }

    public function fetch_environments(?string $uuid = '')
    {
        return $this->fetchDataAndSync(
            'environments',
            'environments',
            Environment::class,
            Environment::getfields,
            $uuid,
            ['branchoffice_id' => Branchoffice::class]// Relación dinámica
        );
    }

    public function fetch_stations(?string $uuid = '')
    {
        return $this->fetchDataAndSync(
            'stations',
            'stations',
            Station::class,
            Station::getfields,
            $uuid,
            ['environment_id' => Environment::class]// Relación dinámica
        );
    }

    public function fetchDataAndSync(
        string $endpoint, // Nombre de la ruta a solicitar
        string $dataKey, // Nombre de la key para obtener la data
        string $modelClass, // Nombre del modelo
        array $fields, // Campos del modelo a sincronizar
        string $authorizationUiid, // Token de autorización
        array $relations = []// Relaciones externas (campo => modelo)
    ) {
        try {
            $endpoint = "https://sistema.360sys.com.pe/api/app-mobile/" . $endpoint;

            $authorizationUiid = $authorizationUiid != '' ? $authorizationUiid : '7554dbfe-74ea-4dfb-b997-47633f9b5761';
            $response = Http::withHeaders([
                'Authorization' => $authorizationUiid,
            ])->get($endpoint);

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $data = $response->json();
                $items = $data['data'][$dataKey] ?? []; // Obtener los datos dinámicamente

                // Asegurarse de que los datos sean siempre un arreglo
                if (isset($items['id'])) {
                    // Si tiene 'id', asumimos que es un solo registro y lo convertimos en un array de un elemento
                    $items = [$items];
                }

                if (!empty($items)) {
                    foreach ($items as $item) {
                        $processedFields = array_intersect_key($item, array_flip($fields));

                        // Procesar relaciones externas (por ejemplo, category_id)
                        foreach ($relations as $field => $relatedModel) {
                            if (isset($item[$field])) {
                                // Buscar el modelo relacionado por server_id
                                $relatedInstance = $relatedModel::where('server_id', $item[$field])->first();
                                $processedFields[$field] = $relatedInstance?->id; // Asignar el ID local si existe
                            }
                        }

                        // Crear o actualizar el modelo según el campo 'server_id'
                        $modelClass::updateOrCreate(
                            ['server_id' => $item['id']], // Buscar por el campo 'server_id'
                            array_merge(
                                $processedFields, // Campos procesados
                                ['server_id' => $item['id']]// Asignar el 'server_id'
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
