<?php

namespace App\Http\Controllers;

use App\Services\BranchOffice\BranchInfoService;

class BranchInfoController extends Controller
{
    protected $branchInfoService;

    // Inyectamos el servicio en el controlador
    public function __construct(BranchInfoService $branchInfoService)
    {
        $this->branchInfoService = $branchInfoService;
    }

    public function getBranchInfo()
    {
        // Usamos el servicio para obtener la información de la sucursal
        $data = $this->branchInfoService->fetchBranchInfo();

        return response()->json($data); // Devolvemos la respuesta
    }
}
