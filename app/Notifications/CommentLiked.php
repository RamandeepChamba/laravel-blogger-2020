<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;
use App\Comment;
use App\Traits\MakeNotification;

class CommentLiked extends Notification
{
    use Queueable;
    use MakeNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, User $liker)
    {
        $this->comment = $comment;
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

    public function prepareData($notifiable)
    {
        $comment = (object)NULL;
        $liker = (object)NULL;
        
        $comment->id = $this->comment->id;
        $comment->comment = $this->comment->comment;
        $comment->blog_id = $this->comment->commentable_id;

        $liker->id = $this->liker->id;
        $liker->name = $this->liker->name;
            
        return [
            'comment' => $comment,
            'liker' => $liker
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
