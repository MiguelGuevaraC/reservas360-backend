<?php
namespace App\Services;

use App\Http\Resources\BranchofficeResource;
use App\Models\Branchoffice;

use Illuminate\Support\Facades\Http;

class BranchInfoService
{

    public function getBranchOfficeById(int $id): ?Branchoffice
    {
        return Branchoffice::find($id);
    }
    public function createBranchOffice(array $data): Branchoffice
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
    
}
