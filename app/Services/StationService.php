<?php
namespace App\Services;

use App\Http\Resources\StationResource;
use App\Models\Station;
use Illuminate\Support\Facades\Http;

class StationService
{

    public function getStationById(int $id): ?Station
    {
        return Station::find($id);
    }
   

}
