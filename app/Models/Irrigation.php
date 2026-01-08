<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Irrigation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crop_id',
        'amount_liters',
        'date',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'amount_liters' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
