<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class ProductCategories extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = [
        'name',
        'user_id',
        'category_isActive'
    ];

    protected function name(): Attribute
    {
        
        return Attribute::make(
            // get: fn ($value) => ucfirst($value),
            set: fn ($value) => strtolower($value),
        );
    }
}
