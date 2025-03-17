<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'time_minutes',
        'status',

        'server_id',
        'category_id',
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
        'price' => 'like',
        'time_minutes' => 'like',
        'status' => 'like',
        'category_id' => '=',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [
        'name' => 'desc',
        'id' => 'desc',
    ];

    const getfields = [
        'name',
        'description',
        'price',
        'time_minutes',
        'status',
        'category_id',
        'server_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
