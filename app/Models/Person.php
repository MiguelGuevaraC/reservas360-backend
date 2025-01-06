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
        'names' => 'asc',
        'fatherSurname' => 'asc',
        'motherSurname' => 'asc',
        'documentNumber' => 'asc',
        'email' => 'asc',
        'state' => 'asc',
    ];
}
