<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'icon',
        'image'
    ];

    protected static function booted()
    {
        static::updating(function ($service) {
            if ($service->isDirty('icon')) {
                $oldIcon = $service->getOriginal('icon');
                if ($oldIcon) {
                    Storage::disk('public')->delete($oldIcon);
                }
            }

            if ($service->isDirty('image')) {
                $oldImage = $service->getOriginal('image');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });

        static::deleting(function ($service) {
            if ($service->icon) {
                Storage::disk('public')->delete($service->icon);
            }

            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
        });
    }
}
