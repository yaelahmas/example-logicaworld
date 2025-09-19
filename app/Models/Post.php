<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'user_id',
        'category_id',
        'status',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    protected $with = ['user', 'category', 'tags'];

    protected static function booted()
    {
        static::updating(function ($post) {
            if ($post->isDirty('thumbnail')) {
                $oldThumbnail = $post->getOriginal('thumbnail');
                if ($oldThumbnail) {
                    Storage::disk('public')->delete($oldThumbnail);
                }
            }
        });

        static::deleting(function ($post) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
}
