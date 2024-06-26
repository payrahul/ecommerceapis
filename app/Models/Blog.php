<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class,'taggable');
    }
}
