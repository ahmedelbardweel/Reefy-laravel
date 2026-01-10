<?php

namespace App\Jobs;

use App\Models\Task;
use App\Notifications\TaskDueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;

    /**
     * Create a new job instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if task still exists and notification not sent
        if ($this->task && !$this->task->notification_sent && $this->task->status !== 'completed') {
            $user = $this->task->user;
            if ($user) {
                $user->notify(new TaskDueNotification($this->task));
                $this->task->notification_sent = true;
                $this->task->save();
            }
        }
    }
}
