<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;




class Person extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'typeofDocument',
        'documentNumber',
        'names',
        'fathersurname',
        'mothersurname',
        'businessName',
   
        'address',
        'phone',

        'email',
        'origin',
        'ocupation',

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
        'names' => 'like',
        'typeofDocument' => 'like',
        'fatherSurname' => 'like',
        'motherSurname' => 'like',
        'documentNumber' => 'like',
        'email' => 'like',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [
        'id' => 'desc',
        'names' => 'desc',
        'fatherSurname' => 'desc',
        'motherSurname' => 'desc',
        'businessName' => 'desc',
        'email' => 'asc',
    ];
}
