<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'url'
    ];

    protected static function booted()
    {
        static::updating(function ($client) {
            if ($client->isDirty('logo')) {
                $oldLogo = $client->getOriginal('logo');
                if ($oldLogo) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }
        });

        static::deleting(function ($client) {
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
        });
    }
}
