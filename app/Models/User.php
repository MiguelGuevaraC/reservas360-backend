<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property( property="id", type="integer", example="1" ),
 *     @OA\Property( property="email", type="string", example="miguel@gmail.com" ),

 *     @OA\Property(property="person_id",type="integer",description="Person Id", example="1"),
 *     @OA\Property(property="person", ref="#/components/schemas/Person") 
 * )
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'person_id',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'password' => 'hashed',
    // ];

    const filters = [
        'name' => 'like',
        'email' => 'like',
        
    ];

    const sorts = [
        'id' => 'desc',
        'name' => 'asc',
        'email' => 'asc',

    ];

    public function person()
    {
        return $this->belongsTo(Person::class,'person_id');
    }
}
