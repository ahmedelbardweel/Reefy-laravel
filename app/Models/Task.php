<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crop_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'category',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the task
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the associated crop if any
     */
    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue()
    {
        return $this->status !== 'completed' && $this->due_date < now();
    }

    /**
     * Mark task as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark task as pending
     */
    public function markAsPending()
    {
        $this->update([
            'status' => 'pending',
            'completed_at' => null,
        ]);
    }
    /**
     * Scope a query to only include tasks due today.
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    /**
     * Scope a query to only include tasks due tomorrow.
     */
    public function scopeDueTomorrow($query)
    {
        return $query->whereDate('due_date', today()->addDay());
    }

    /**
     * Scope a query to only include tasks due this week.
     */
    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [today(), today()->addWeek()]);
    }

    /**
     * Scope a query to only include overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                     ->where('due_date', '<', now());
    }
}
