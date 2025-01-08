<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
        'description',
        'photo',
        'stock',
        'price',
        'status',
        'category_id',
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
        'description' => 'like',
        'stock' => 'like',
        'price' => 'like',
        'status' => 'like',
        'category.name' => 'like',
    ];

    /**
     * Campos de ordenación disponibles.
     */
    const sorts = [

        'name' => 'desc',
        'id' => 'desc',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
}
