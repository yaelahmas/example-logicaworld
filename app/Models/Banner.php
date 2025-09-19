<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link_url',
        'order_no',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::updating(function ($banner) {
            if ($banner->isDirty('image')) {
                $oldImage = $banner->getOriginal('image');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });

        static::deleting(function ($banner) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
        });
    }
}
