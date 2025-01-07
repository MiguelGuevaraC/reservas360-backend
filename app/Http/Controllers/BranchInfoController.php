<?php

namespace App\Http\Controllers;

use App\Services\BranchOffice\BranchInfoService;
use App\Services\Company\CompanyService;

class BranchInfoController extends Controller
{
    protected $companyService;
    protected $branchInfoService;

    // Inyectamos el servicio en el controlador
    public function __construct(BranchInfoService $branchInfoService,CompanyService $companyService)
    {
        $this->branchInfoService = $branchInfoService;
        $this->companyService = $companyService;
    }

    public function getBranchInfo()
    {
        // Usamos el servicio para obtener la información de la sucursal
        $this->companyService->fetchCompanyData();
        $data = $this->branchInfoService->fetchBranchInfo();

        return response()->json($data); // Devolvemos la respuesta
    }


}
