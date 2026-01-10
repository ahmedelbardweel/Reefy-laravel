<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDueNotification;

class CheckTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for due tasks and send notifications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = Task::where('reminder_date', '<=', now())
                     ->where('notification_sent', false)
                     ->where('status', '!=', 'completed')
                     ->with('user')
                     ->get();

        foreach ($tasks as $task) {
            if ($task->user) {
                // Using the TaskDueNotification we created
                $task->user->notify(new TaskDueNotification($task));
                
                // Mark as sent so we don't spam
                $task->update(['notification_sent' => true]);
                
                $this->info("Notification sent for task: {$task->id} - {$task->title}");
            }
        }

        return Command::SUCCESS;
    }
}
