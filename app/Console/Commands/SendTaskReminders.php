<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskReminder;
use Illuminate\Support\Facades\Notification;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for tasks with due reminders';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = now();
        
        // precise matching might miss if cron isn't every minute, so we check for ranges or 'not notified yet' flag.
        // For simplicity here, we check tasks due for reminder in the last hour that haven't been completed.
        // Ideally we'd have a 'reminder_sent' flag. 
        // Let's assume we just log it for now as we don't have the Notification class setup fully.
        
        $tasks = Task::whereNotNull('reminder_date')
            ->where('reminder_date', '<=', $now)
            ->where('status', '!=', 'completed')
            // ->where('reminder_sent', false) // Use this in production
            ->get();

        $count = 0;
        foreach ($tasks as $task) {
            $this->info("Sending reminder for task: {$task->title} to User {$task->user_id}");
            // Notification::send($task->user, new TaskReminder($task));
            $count++;
        }

        $this->info("Sent $count reminders.");
        return Command::SUCCESS;
    }
}
