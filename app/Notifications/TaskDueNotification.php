<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;

class TaskDueNotification extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'تذكير بمهمة',
            'body' => $this->task->title . ' - موعدها: ' . $this->task->due_date->format('Y-m-d H:i'),
            'task_id' => $this->task->id,
            'url' => route('tasks.show', $this->task->id),
            'icon' => 'notification_important',
            'color' => 'red'
        ];
    }
}
