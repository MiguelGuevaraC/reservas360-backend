<?php
namespace App\Services;

use App\Http\Resources\ServiceResource;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServiceService
{

    public function getServiceById(int $id): ?Service
    {
        return Service::find($id);
    }     
    

}
