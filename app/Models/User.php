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
        'dob',
        'country_id'
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

    public function getUserSettingsData()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function productData()
    {
        return $this->hasMany(Product::class,'user_id');
    }

    public function userProducts()
    {
        return $this->belongsToMany(Product::class,'users_products','users_id','products_id');
    }

    public function phoneNumber()
    {
        // return $this->hasOneThrough(Phone_number::class,Company::class);

        return $this->hasOneThrough(Phone_number::class,Company::class,'user_id','campanys_id','id','id');

    }

    public function companyName()
    {
        return $this->hasOne(Company::class,'user_id');
    }

    public function latestOrder()
    {
        return $this->hasOne(Product::class)->latestOfMany();
    }

    public function oldestOrder()
    {
        return $this->hasOne(Product::class)->oldestOfMany();
    }

    public function largestOrder()
    {
        return $this->hasOne(Product::class)->ofMany('product_price','max');
    }

    public function smallestOrder()
    {
        return $this->hasOne(Product::class)->ofMany('product_price','min');
    }

    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
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
