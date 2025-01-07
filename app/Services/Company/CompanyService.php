<?php
namespace App\Services\Company;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Support\Facades\Http;

class CompanyService
{

    public function getCompanyById(int $id): ?Company
    {
        return Company::find($id);
    }

   
    public function createCompany(array $data): Company
    {
        return Company::create($data);
    }

    public function updateCompany($company, array $data)
    {
        $company->update($data);
        return $company;
    }

    public function destroyById($id)
{
    $company = Company::find($id);

    if (!$company) {
        return false; 
    }
    return $company->delete(); // Devuelve true si la eliminación fue exitosa
}

    public function fetchCompanyData()
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
                $companyData = $data['data']['company'] ?? null;

                if ($companyData) {
                    // Verificar si la empresa ya existe en la base de datos utilizando el campo 'ruc'
                    $existingCompany = Company::where('server_id', $companyData['id'])->first();

                    if (!$existingCompany) {
                        // Si no existe, crear una nueva empresa con los datos obtenidos
                        $company = Company::create([

                            'numberDocument' => $companyData['ruc'],
                            'businessName' => $companyData['name'] ?? '',
                            'address' => $companyData['address'] ?? 'No disponible',
                            'phone' => $companyData['phone'] ?? 'No disponible',
                            'email' => $companyData['email'] ?? 'No disponible',
                            'server_id' => $companyData['id'] ?? '',

                        ]);

                        // Usamos el recurso para devolver la respuesta
                        new CompanyResource($company);
                    } else {
                        // Si existe, actualizar la empresa con los nuevos datos
                        $existingCompany->update([

                            'numberDocument' => $companyData['ruc'],
                            'businessName' => $companyData['name'] ?? $existingCompany->businessName,
                            'address' => $companyData['address'] ?? $existingCompany->address,
                            'phone' => $companyData['phone'] ?? $existingCompany->phone,
                            'email' => $companyData['email'] ?? $existingCompany->email,
                            'server_id' => $companyData['id'] ?? $existingCompany->server_id,

                        ]);

                        // Devolver la empresa actualizada usando el recurso
                        new CompanyResource($existingCompany);
                    }
                    return ([
                        'status' => true,
                        'message' => 'Data Actualizada de Empresas',
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
