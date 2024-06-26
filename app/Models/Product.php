<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'product_discount',
        'product_image',
        'user_id',
        'category_id',
        'product_isActive'
    ];

    public function userDataOnetomanyinverse()
    {
        return $this->belongsTo(User::class,'user_id');
    }


    
}
