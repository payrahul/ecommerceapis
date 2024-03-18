<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishCartOrder extends Model
{
    use HasFactory;

    protected $table = 'wish_cart_orders';

    protected $fillable = ['wish_id','cart_id','order_id','user_id','wco_isActive'];
}
