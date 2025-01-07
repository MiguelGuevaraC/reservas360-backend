<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branchoffice extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
        'address',
        'state',
        'server_id',
        
        'company_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $hidden = [

        'created_at',
        'updated_at',
        'deleted_at',
    ];
    const filters = [
        'name' => 'like',
        'address' => 'like',
        'company.numberDocument' => 'like',
        'company.busisnessName' => 'like',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [
        'id' => 'desc',
        'name' => 'desc',
        'address' => 'desc',
 
    ];

    
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}
