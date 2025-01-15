<?php
namespace App\Models;

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
        'branch_id',

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
        'name'              => 'like',
        'description'       => 'like',
        'route'             => 'like',
        'status'            => 'like',
        'branchoffice.name' => 'like',
        'branch_id'         => '=',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [
        'id'          => 'desc',
        'name'        => 'desc',
        'description' => 'desc',

    ];
    const getfields = [
        'name',
        'description',
        'route',
        'status',
        'server_id',
        'branch_id',
    ];
    public function branchoffice()
    {
        return $this->belongsTo(Branchoffice::class, 'branch_id');
    }
    public function stations()
    {
        return $this->hasMany(Station::class, 'environment_id');
    }
}
