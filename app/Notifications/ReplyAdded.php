<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;
use App\Comment;
use App\Traits\MakeNotification;

class ReplyAdded extends Notification
{
    use Queueable;
    use MakeNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Comment $parentComment, Comment $reply, User $replier)
    {
        $this->parentComment = $parentComment;
        $this->reply = $reply;
        $this->replier = $replier;
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
        $parentComment = (object)NULL;
        $blog = (object)NULL;
        $reply = (object)NULL;
        $replier = (object)NULL;
        
        $parentComment->id = $this->parentComment->id;
        $parentComment->comment = $this->parentComment->comment;

        $reply->id = $this->reply->id;
        $reply->comment = $this->reply->comment;

        $blog->id = $this->reply->commentable_id;

        $replier->id = $this->replier->id;
        $replier->name = $this->replier->name;
            
        return [
            'blog' => $blog,
            'parentComment' => $parentComment,
            'reply' => $reply,
            'replier' => $replier
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
