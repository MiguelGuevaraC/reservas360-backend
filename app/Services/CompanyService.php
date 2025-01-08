<?php
namespace App\Services;

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
                $companiesData = $data['data']['company'] ?? null;
    
                if (is_array($companiesData)) { // Validar que sea un array antes de iterar
                    foreach ($companiesData as $companyData) {
                        if (is_array($companyData)) { // Validar que cada elemento sea un array
                            // Verificar si la empresa ya existe en la base de datos utilizando el campo 'server_id'
                            $existingCompany = Company::where('server_id', $companyData['id'] ?? null)->first();
    
                            if (!$existingCompany) {
                                // Si no existe, crear una nueva empresa con los datos obtenidos
                                $company = Company::create([
                                    'numberDocument' => $companyData['ruc'] ?? '',
                                    'businessName' => $companyData['name'] ?? '',
                                    'address' => $companyData['address'] ?? '',
                                    'phone' => $companyData['phone'] ?? '',
                                    'email' => $companyData['email'] ?? '',
                                    'server_id' => $companyData['id'] ?? '',
                                ]);
    
                                // Usamos el recurso para devolver la respuesta
                                new CompanyResource($company);
                            } else {
                                // Si existe, actualizar la empresa con los nuevos datos
                                $existingCompany->update([
                                    'numberDocument' => $companyData['ruc'] ?? $existingCompany->numberDocument,
                                    'businessName' => $companyData['name'] ?? $existingCompany->businessName,
                                    'address' => $companyData['address'] ?? $existingCompany->address,
                                    'phone' => $companyData['phone'] ?? $existingCompany->phone,
                                    'email' => $companyData['email'] ?? $existingCompany->email,
                                    'server_id' => $companyData['id'] ?? $existingCompany->server_id,
                                ]);
    
                                // Devolver la empresa actualizada usando el recurso
                                new CompanyResource($existingCompany);
                            }
                        }
                    }
                } else {
                    return ([
                        'status' => false,
                        'message' => 'El formato de los datos de la empresa no es válido.',
                    ]);
                }
    
                return [
                    'status' => true,
                    'message' => 'Datos actualizados de empresas.',
                ];
            }
    
            // En caso de error en la respuesta
            return ([
                'status' => false,
                'message' => 'Error al obtener datos de la API externa.',
            ]);
        } catch (\Exception $e) {
            // Manejo de cualquier excepción
            return ([
                'status' => false,
                'message' => 'Error interno: ' . $e->getMessage(),
            ]);
        }
    }
    

}
