<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'area',
        'planting_date',
        'harvest_date',
        'status',
        'water_level',
        'image_url',
        'field_name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'planting_date' => 'date',
        'harvest_date' => 'date',
        'water_level' => 'integer',
    ];

    /**
     * Get the user that owns the crop
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the irrigations for the crop.
     */
    public function irrigations()
    {
        return $this->hasMany(Irrigation::class);
    }

    /**
     * Get the tasks for the crop.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
