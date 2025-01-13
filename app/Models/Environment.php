<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Environment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
        'description',
        'route',
        'status',
        'server_id',
        'branchoffice_id',
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
        'description' => 'like',
        'route' => 'like',
        'status' => 'like',
        'branchoffice.name' => 'like',
        'branchoffice_id' => '=',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [
        'id' => 'desc',
        'name' => 'desc',
        'description' => 'desc',

    ];

    public function branchoffice()
    {
        return $this->belongsTo(Branchoffice::class,'branchoffice_id');
    }
}
