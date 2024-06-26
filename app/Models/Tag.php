<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function blogs()
    {
        return $this->morphedByMany(Blog::class,'taggable');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class,'taggable');
    }
}
