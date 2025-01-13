<?php
namespace App\Services;

use App\Models\Environment;
use Illuminate\Support\Facades\Storage;

class EnvironmentService
{

    public function getEnvironmentById(int $id): ?Environment
    {
        return Environment::find($id);
    }


}
