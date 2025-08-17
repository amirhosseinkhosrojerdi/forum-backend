<?php

namespace App\Notifications;

use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewReplySubmitted extends Notification
{
    use Queueable;

    /**
     * The thread instance.
     *
     * @var Thread
     */
    private $thread;

    /**
     * Create a new notification instance.
     *
     * @param  Thread  $thread
     * @return void
     */
    public function __construct(Thread $thread)
    {
        //
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

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'thread_title' => $this->thread->title,
            'url' => route('threads.show', [$this->thread]),
            'time' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
