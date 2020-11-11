<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;
use App\Blog;
use App\Traits\MakeNotification;

class FollowingAddedBlog extends Notification
{
    use Queueable;
    use MakeNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $leader, Blog $blog)
    {
        $this->leader = $leader;
        $this->blog = $blog;
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

    public function prepareData($notifiable)
    {
        $leader = (object)NULL;
        $blog = (object)NULL;

        $leader->id = $this->leader->id;
        $leader->name = $this->leader->name;

        $blog->id = $this->blog->id;
        $blog->title = $this->blog->title;

        return [
            'leader' => $leader,
            'blog' => $blog
        ];
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
