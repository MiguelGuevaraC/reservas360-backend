<?php
namespace App\Services\BranchOffice;

use App\Http\Resources\CompanyResource;
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

                // Suponiendo que la respuesta contiene el campo 'company'
                $branch_info_data = $data['data']['branch'] ?? null;

                if ($branch_info_data) {
                    // Verificar si la empresa ya existe en la base de datos utilizando el campo 'ruc'
                    $existingBranchOffice = Branchoffice::where('server_id', $branch_info_data['id'])->first();
                    $existingCompany = Company::where('numberDocument', $branch_info_data['ruc'])->first();
                    if (!$existingBranchOffice) {
                        // Si no existe, crear una nueva empresa con los datos obtenidos
                        $company = Branchoffice::create([

                            'name' => $branch_info_data['brand_name'],

                            'address' => $branch_info_data['address'] ?? 'No disponible',
                            'phone' => $branch_info_data['telephone'] ?? 'No disponible',
                            'email' => $branch_info_data['email'] ?? 'No disponible',
                            'company_id' => $existingCompany->id ??null,
                            'server_id' => $branch_info_data['id'] ?? '',

                        ]);

                        // Usamos el recurso para devolver la respuesta
                        new CompanyResource($company);
                    } else {
                        // Si existe, actualizar la empresa con los nuevos datos
                        $existingBranchOffice->update([

                            'name' => $branch_info_data['ruc'],

                            'address' => $branch_info_data['address'] ?? $existingBranchOffice->address,
                            'phone' => $branch_info_data['telephone'] ?? $existingBranchOffice->phone,
                            'email' => $branch_info_data['email'] ?? $existingBranchOffice->email,
                            'company_id' => $existingCompany->id  ?? $existingBranchOffice->company_id,
                            'server_id' => $branch_info_data['id'] ?? $existingBranchOffice->server_id,

                        ]);

                        // Devolver la empresa actualizada usando el recurso
                        new CompanyResource($existingBranchOffice);
                    }
                    return ([
                        'status' => true,
                        'message' => 'Data Actualizada de Sucursales',
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'No se encontró la información de la empresa en la respuesta.',
                    ]);
                }
            }

            // En caso de error en la respuesta
            return response()->json([
                'status' => false,
                'message' => 'Error al obtener datos de la API externa.',
            ]);
        } catch (\Exception $e) {
            // Manejo de cualquier excepción
            return response()->json([
                'status' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ]);
        }
    }
}
