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
        'brand_name',
        'ruc',
        'name',
        'address',
        'phone',
        'telephone',
        'email',
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
    const getfields = [
        'brand_name',
        'ruc',
        'name',
        'address',
        'phone',
        'telephone',
        'email',
        'server_id',
        'company_id',
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function environments()
    {
        return $this->hasMany(Environment::class,'branch_id');
    }
    public function categories()
    {
        return $this->hasMany(Category::class,'branch_id');
    }
    
}
