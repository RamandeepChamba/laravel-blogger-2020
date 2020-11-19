<?php

namespace App\Traits\Notifications\PrepareData;

trait CommentLiked {
    function prepareData($notifiable, $comment, $liker)
    {
        $prettyComment = (object)NULL;
        $prettyLiker = (object)NULL;
        
        $prettyComment->id = $comment->id;
        $prettyComment->comment = $comment->comment;
        $prettyComment->blog_id = $comment->commentable_id;

        $prettyLiker->id = $liker->id;
        $prettyLiker->name = $liker->name;
            
        return [
            'comment' => $prettyComment,
            'liker' => $prettyLiker
        ];
    }
}