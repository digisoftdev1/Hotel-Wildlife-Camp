<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogRedirect extends Model
{
    protected $fillable = [
        'blog_id',
        'old_slug'
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}