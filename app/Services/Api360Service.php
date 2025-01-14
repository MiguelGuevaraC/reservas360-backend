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
            null,
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
            null,
            ['company_id' => Company::class]// Relación dinámica
        );
    }

    public function fetch_categories(?string $uuid = '')
    {
        // Obtener la primera sucursal
        $branch = $this->fetch_branches($uuid);

        // Verificar si la respuesta tiene datos de la sucursal
        if (($branch['data']['branch'])) {

            $firstBranch = $branch['data']['branch']; // Asegúrate de que el formato de respuesta es correcto

            // Buscar la sucursal en el modelo Branchoffice por server_id
            $branchOffice = Branchoffice::where('server_id', $firstBranch['id'])->first();

            if ($branchOffice) {
                return $this->fetchDataAndSync(
                    'categories',
                    'categories',
                    Category::class,
                    Category::getfields,
                    $uuid,
                    $branchOffice,
                    ['branchoffice_id' => Branchoffice::class]// Relación dinámica
                );
            } else {
                return [
                    'status' => false,
                    'message' => "No se encontró la sucursal con server_id: {$firstBranch['id']}.",
                    'data' => [],
                ];
            }
        }

        return [
            'status' => false,
            'message' => "No se pudo obtener la primera sucursal.",
            'data' => [],
        ];
    }

    public function fetch_products(?string $uuid = '')
    {
        return $this->fetchDataAndSync(
            'products-and-services',
            'products',
            Product::class,
            Product::getfields,
            $uuid, null,
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
            $uuid, null,
            ['category_id' => Category::class]// Relación dinámica
        );
    }

    public function fetch_stations(?string $uuid = '')
    {
        // Obtener la primera sucursal
        $branch = $this->fetch_branches($uuid);

        // Verificar si la respuesta tiene datos de la sucursal
        if (($branch['data']['branch'])) {

            $firstBranch = $branch['data']['branch']; // Asegúrate de que el formato de respuesta es correcto

            // Buscar la sucursal en el modelo Branchoffice por server_id
            $branchOffice = Branchoffice::where('server_id', $firstBranch['id'])->first();

            if ($branchOffice) {
                return $this->fetchDataAndSync(
                    'stations',
                    'stations',
                    Station::class,
                    Station::getfields,
                    $uuid,
                    $branchOffice,
                    ['branchoffice_id' => Branchoffice::class]// Relación dinámica
                );
            } else {
                return [
                    'status' => false,
                    'message' => "No se encontró la sucursal con server_id: {$firstBranch['id']}.",
                    'data' => [],
                ];
            }
        }

        return [
            'status' => false,
            'message' => "No se pudo obtener la primera sucursal.",
            'data' => [],
        ];
    }

    public function fetch_environments(?string $uuid = '')
    {
        // Obtener la primera sucursal
        $branch = $this->fetch_branches($uuid);

        // Verificar si la respuesta tiene datos de la sucursal
        if (($branch['data']['branch'])) {

            $firstBranch = $branch['data']['branch']; // Asegúrate de que el formato de respuesta es correcto

            // Buscar la sucursal en el modelo Branchoffice por server_id
            $branchOffice = Branchoffice::where('server_id', $firstBranch['id'])->first();

            if ($branchOffice) {
                return $this->fetchDataAndSync(
                    'environments',
                    'environments',
                    Environment::class,
                    Environment::getfields,
                    $uuid,
                    $branchOffice,
                    ['branchoffice_id' => Branchoffice::class]// Relación dinámica
                );
            } else {
                return [
                    'status' => false,
                    'message' => "No se encontró la sucursal con server_id: {$firstBranch['id']}.",
                    'data' => [],
                ];
            }
        }

        return [
            'status' => false,
            'message' => "No se pudo obtener la primera sucursal.",
            'data' => [],
        ];
    }

    public function fetchDataAndSync(
        string $endpoint, // Nombre de la ruta a solicitar
        string $dataKey, // Nombre de la key para obtener la data
        string $modelClass, // Nombre del modelo
        array $fields, // Campos del modelo a sincronizar
        string $authorizationUiid, // Token de autorización
        ?Branchoffice $branchoffice = null, // Oficina relacionada, si aplica
        array $relations = []// Relaciones externas (campo => modelo)
    ) {
        try {
            // Construir el endpoint completo
            $endpoint = "https://sistema.360sys.com.pe/api/app-mobile/" . $endpoint;

            // Token de autorización predeterminado si no se proporciona
            $authorizationUiid = !empty($authorizationUiid) ? $authorizationUiid : '7554dbfe-74ea-4dfb-b997-47633f9b5761';

            // Realizar la solicitud HTTP
            $response = Http::withHeaders(['Authorization' => $authorizationUiid])->get($endpoint);

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $data = $response->json();
                $items = $data['data'][$dataKey] ?? []; // Obtener los datos dinámicamente

                // Normalizar los datos en un arreglo
                if (isset($items['id'])) {
                    $items = [$items]; // Convertir un solo registro en un arreglo
                }

                // Procesar los datos si existen elementos
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $processedFields = array_intersect_key($item, array_flip($fields)); // Filtrar campos permitidos

                        // Procesar relaciones externas
                        foreach ($relations as $field => $relatedModel) {
                            if (isset($item[$field])) {
                                $relatedInstance = $relatedModel::where('server_id', $item[$field])->first();
                                $processedFields[$field] = $relatedInstance?->id ?? null; // Asignar el ID local o null
                            }
                        }

                        // Si la relación branchoffice existe, asignar su ID
                        if ($branchoffice !== null && !isset($processedFields['branchoffice_id'])) {
                            $processedFields['branchoffice_id'] = $branchoffice->id;
                        }

                        // Crear o actualizar el modelo según el campo 'server_id'
                        $modelClass::updateOrCreate(
                            ['server_id' => $item['id']], // Condición de búsqueda
                            array_merge(
                                $processedFields, // Campos procesados
                                ['server_id' => $item['id']]// Asignar el 'server_id'
                            )
                        );
                    }

                    return [
                        'status' => true,
                        'message' => "Datos sincronizados correctamente para el modelo {$modelClass}.",
                        'data' => $data['data'],
                    ];
                }

                // Manejo de caso sin datos
                return [
                    'status' => false,
                    'message' => "No se encontraron datos en la clave '{$dataKey}'.",
                    'data' => [],
                ];
            }

            // Manejo de respuesta no exitosa
            return [
                'status' => false,
                'message' => 'La solicitud a la API no fue exitosa.',
                'data' => [],
            ];
        } catch (\Exception $e) {
            // Manejo de excepciones
            return [
                'status' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ];
        }
    }

}
