<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'user_id',
        'category_isActive'
    ];

    public function products(){
        return $this->hasMany(Product::class,'category_id');
    }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class,'category_id');
    // }

    // public function products()
    // {
    //     return $this->hasMany(Product::class,'category_id');
    // }
}
