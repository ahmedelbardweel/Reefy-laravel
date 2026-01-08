<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language',
        'units',
        'weather_alerts',
        'irrigation_reminders',
        'crop_updates',
    ];

    protected $casts = [
        'weather_alerts' => 'boolean',
        'irrigation_reminders' => 'boolean',
        'crop_updates' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
