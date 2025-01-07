<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'numberDocument',
        'businessName',
        'address',
        'phone',
        'email',
        'state',
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
        'businessName' => 'like',
        'numberDocument' => 'like',
        'ruc' => 'like',
        'address' => 'like',
        'email' => 'like',
        'phone' => 'like',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [
        'businessName' => 'desc',
        'ruc' => 'desc',
        'address' => 'desc',
    ];


    public function branchoffices()
    {
        return $this->hasMany(Branchoffice::class);
    }
}
