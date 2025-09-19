<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_position',
        'company',
        'message',
        'photo',
        'rating'
    ];

    protected static function booted()
    {
        static::updating(function ($testimonial) {
            if ($testimonial->isDirty('photo')) {
                $oldPhoto = $testimonial->getOriginal('photo');
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
        });

        static::deleting(function ($testimonial) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
        });
    }
}
