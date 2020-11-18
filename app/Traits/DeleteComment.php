<?php

namespace App\Traits;

use App\Comment;
use App\Events\BlogUpdated as BlogUpdatedEvent;

trait DeleteComment {
    public function deleteComment($id, $blogBeingDeleted = false)
    {
        $comment = Comment::findOrFail($id);
        // Check if auth user has right
        if (($comment->user_id !== auth()->user()->id) && !$blogBeingDeleted) {
            abort(401, "Not your comment");
        }
        // Delete comment and replies
        $response = $this->deleteWithReplies($id);
        BlogUpdatedEvent::dispatch($comment->commentable_id, $comment->user_id);
        return $response;
    }

    public function deleteWithReplies($id)
    {
        $comment = Comment::findOrFail($id);

        // Delete replies if any
        foreach ($comment->replies as $reply) {
            $this->deleteWithReplies($reply->id);
        }

        $response = (object)NULL;
        $response->comment = $comment;
        
        // Delete likes
        $comment->likes()->delete();
        // Delete comment/reply
        $comment->delete();
        
        return json_encode($response);
    }
}
?>