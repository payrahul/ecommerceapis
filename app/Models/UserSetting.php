<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',
        'isImageVisible',
        'address',
        'isUserPaid',
        'us_isActive'
    ];

    public function usersData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
