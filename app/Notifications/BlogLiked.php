<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\User;
use App\Blog;
use App\Traits\MakeNotification;
use App\Traits\Notifications\PrepareData\BlogLiked as PrepareData;

class BlogLiked extends Notification
{
    use Queueable;
    use MakeNotification;
    use PrepareData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Blog $blog, User $liker)
    {
        $this->blog = $blog;
        $this->liker = $liker;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    private function prepareMyData($notifiable)
    {
        return $this->prepareData($notifiable, $this->blog, $this->liker);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
