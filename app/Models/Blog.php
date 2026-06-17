<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'blog_title',
        'slug',
        'content',
        'featured_image',
        'category_id',
        'excerpt',
        'keywords',
        'status',
        'is_featured',
        'read_time',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'read_time' => 'integer',
        'keywords' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            if ($blog->status === 'published' && empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->blog_title);
            }
        });

        static::updating(function ($blog) {
            $isBeingPublished = $blog->isDirty('status') && $blog->status === 'published';
            $titleChanged = $blog->isDirty('blog_title');

            if ($isBeingPublished && empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->blog_title);
            } elseif ($titleChanged && !empty($blog->slug)) {
                $newSlug = static::generateUniqueSlug($blog->blog_title, $blog->id);

                if ($newSlug !== $blog->slug) {
                    BlogRedirect::create([
                        'blog_id' => $blog->id,
                        'old_slug' => $blog->slug
                    ]);

                    $blog->slug = $newSlug;
                }
            }
        });
    }

    public static function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        while (static::slugExists($slug, $ignoreId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }
    protected static function slugExists($slug, $ignoreId = null)
    {
        $query = static::where('slug', $slug);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists() || BlogRedirect::where('old_slug', $slug)->exists();
    }
    public static function findBySlugOrRedirect($slug)
    {
        $blog = static::where('slug', $slug)->first();
        if ($blog) {
            return ['blog' => $blog, 'redirect' => false];
        }
        $redirect = BlogRedirect::where('old_slug', $slug)->first();

        if ($redirect) {
            return ['blog' => $redirect->blog, 'redirect' => true];
        }

        return null;
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function redirects()
    {
        return $this->hasMany(BlogRedirect::class);
    }

    public static function calculateReadTime($content)
    {
        $text = strip_tags($content);
        $wordCount = str_word_count($text);
        $minutes = ceil($wordCount / 200);
        return max(1, $minutes);
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function hasSlug()
    {
        return !empty($this->slug);
    }
}