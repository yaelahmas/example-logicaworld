<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type'];

    public static function setValue(string $key, string $value, string $type = 'text'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type'  => $type,
            ]
        );
    }

    public static function getValue(string $key, $default = null)
    {
        return optional(static::where('key', $key)->first())->value ?? $default;
    }

    // public function getTypeAttribute(): string
    // {
    //     return match ($this->key) {
    //         'site_logo', 'site_favicon', 'site_og_image' => 'image',
    //         default => 'text',
    //     };
    // }

    protected static function booted()
    {
        static::updating(function ($setting) {
            if ($setting->type === 'image' && $setting->isDirty('value')) {
                $oldImage = $setting->getOriginal('value');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });

        static::deleting(function ($setting) {
            if ($setting->type === 'image' && $setting->value) {
                Storage::disk('public')->delete($setting->value);
            }
        });
    }
}
