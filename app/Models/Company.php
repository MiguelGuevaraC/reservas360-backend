<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'ruc',
        'name',
        'address',
        'phone',
        'telephone',
        'email',
        'status',
        'server_id',
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
        'numberDocument' => 'like',
        'ruc' => 'like',
        'address' => 'like',
        'email' => 'like',
        'phone' => 'like',
        'telephone' => 'like',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [
        'name' => 'desc',
        'ruc' => 'desc',
        'address' => 'desc',
    ];
    const getfields = [
        'ruc',
        'name',
        'address',
        'phone',
        'telephone',
        'email',
        'state',
        'server_id'
    ];

    public function branchoffices()
    {
        return $this->hasMany(Branchoffice::class);
    }


}
