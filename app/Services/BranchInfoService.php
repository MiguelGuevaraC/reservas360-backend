<?php
namespace App\Services;

use App\Http\Resources\BranchofficeResource;
use App\Models\Branchoffice;
use App\Models\Company;
use Illuminate\Support\Facades\Http;

class BranchInfoService
{

    public function getBranchOfficeById(int $id): ?Company
    {
        return Branchoffice::find($id);
    }
    public function createBranchOffice(array $data): Company
    {
        return Branchoffice::create($data);
    }

    public function updateBranchOffice($branchOffice, array $data)
    {
        $branchOffice->update($data);
        return $branchOffice;
    }
    public function destroyById($id)
    {
        $branchOffice = Branchoffice::find($id);

        if (!$branchOffice) {
            return false;
        }
        return $branchOffice->delete(); // Devuelve true si la eliminación fue exitosa
    }

    // El método recibe el UUID de la sucursal
    public function fetchBranchInfo()
    {
        try {
            // Realizar la solicitud GET con los headers necesarios
            $response = Http::withHeaders([
                'Authorization' => '7554dbfe-74ea-4dfb-b997-47633f9b5761', // Token de autorización
            ])->get('https://sistema.360sys.com.pe/api/app-mobile/branch-info');

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                // Obtener los datos de la respuesta
                $data = $response->json();

                // Suponiendo que la respuesta contiene el campo 'branch'
                $branch_info_data = $data['data']['branch'] ?? null;

                // Validar que branches_info_data sea un array
                if (is_array($branch_info_data)) {

                    // Validar que cada elemento sea un array antes de acceder a sus índices
                    if (is_array($branch_info_data)) {
                        // Verificar si la sucursal ya existe en la base de datos utilizando 'id'
                        $existingBranchOffice = Branchoffice::where('server_id', $branch_info_data['id'] ?? null)->first();
                        $existingCompany = Company::where('numberDocument', $branch_info_data['ruc'] ?? null)->first();

                        if (!$existingBranchOffice) {
                            // Crear una nueva sucursal si no existe
                            $newBranchOffice = Branchoffice::create([
                                'name' => $branch_info_data['brand_name'] ?? 'Nombre no disponible',
                                'address' => $branch_info_data['address'] ?? 'No disponible',
                                'phone' => $branch_info_data['telephone'] ?? 'No disponible',
                                'email' => $branch_info_data['email'] ?? 'No disponible',
                                'company_id' => $existingCompany->id ?? null,
                                'server_id' => $branch_info_data['id'] ?? '',
                            ]);

                            // Usamos el recurso para devolver la respuesta (opcional)
                            new BranchofficeResource($newBranchOffice);
                        } else {
                            // Si existe, actualizar la sucursal con los nuevos datos
                            $existingBranchOffice->update([
                                'name' => $branch_info_data['brand_name'] ?? $existingBranchOffice->name,
                                'address' => $branch_info_data['address'] ?? $existingBranchOffice->address,
                                'phone' => $branch_info_data['telephone'] ?? $existingBranchOffice->phone,
                                'email' => $branch_info_data['email'] ?? $existingBranchOffice->email,
                                'company_id' => $existingCompany->id ?? $existingBranchOffice->company_id,
                                'server_id' => $branch_info_data['id'] ?? $existingBranchOffice->server_id,
                            ]);

                            // Usamos el recurso para devolver la respuesta (opcional)
                            new BranchofficeResource($existingBranchOffice);
                        }
                    } else {
                        // Log o mensaje si $branch_info_data no es válido
                        return response()->json([
                            'status' => false,
                            'message' => 'Formato inválido en los datos de una sucursal.',
                        ]);
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Datos de sucursales actualizados correctamente.',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'No se encontró información válida de sucursales.',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'La solicitud no fue exitosa.',
                ]);
            }

            // En caso de error en la respuesta

        } catch (\Exception $e) {
            // Manejo de cualquier excepción
            return response()->json([
                'status' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ]);
        }
    }
}
