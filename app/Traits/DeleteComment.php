<?php

namespace App\Traits;

use App\Comment;

trait DeleteComment {
    public function deleteComment($id, $blogBeingDeleted = false)
    {
        $comment = Comment::findOrFail($id);
        // Check if auth user has right
        if (($comment->user_id !== auth()->user()->id) && !$blogBeingDeleted) {
            abort(401, "Not your comment");
        }
        // Delete comment and replies
        return $this->deleteWithReplies($id);
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