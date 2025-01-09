<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
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
        'status' => 'like',
    ];


    const getfields = [
        'name',
        'status',
        'server_id',
    ];
    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [

        'name' => 'desc',
        'id' => 'desc',
    ];
}
