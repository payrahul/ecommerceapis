<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'user_isActive',
        'gender',
        'mobile',
        'dob'
    ];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }
    
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function getDobAttribute($value)
    {
        // For example, convert 1/0 to true/false
        return $value - 1;
    }

    public function getUserisActiveAttribute($value)
    {
        // For example, convert 1/0 to true/false
        return "test".$value;
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function userProduct()
    {
        return $this->belongsToMany(Product::class,'users_products','users_id', 'products_id' );
    
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
