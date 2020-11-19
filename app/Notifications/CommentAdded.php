<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Blog;
use App\Comment;
use App\User;
use App\Traits\MakeNotification;

class CommentAdded extends Notification
{
    use Queueable;
    use MakeNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Blog $blog, Comment $comment, User $commenter)
    {
        $this->blog = $blog;
        $this->comment = $comment;
        $this->commenter = $commenter;
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

    public function prepareMyData($notifiable)
    {
        $blog = (object)NULL;
        $comment = (object)NULL;
        $commenter = (object)NULL;
        
        $blog->id = $this->blog->id;
        $blog->title = $this->blog->title;

        $comment->id = $this->comment->id;
        $comment->comment = $this->comment->comment;

        $commenter->id = $this->commenter->id;
        $commenter->name = $this->commenter->name;
            
        return [
            'blog' => $blog,
            'comment' => $comment,
            'commenter' => $commenter
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
