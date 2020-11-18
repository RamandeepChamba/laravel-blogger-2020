<?php

namespace App\Traits;

use App\Blog;
use App\Events\BlogUpdated as BlogUpdatedEvent;

trait DeleteBlog {

    use DeleteComment;

    public function deleteBlog($id, $userBeingDeleted = false)
    {
        $blog = Blog::findOrFail($id);
        // Check if auth user has right
        if (($blog->user_id !== auth()->user()->id) && !$userBeingDeleted) {
            abort(401, "Not your blog");
        }
        // Delete likes
        $blog->likes()->delete();
        // Delete comments with likes and replies
        foreach ($blog->comments as $comment) {
            $this->deleteComment($comment->id, true);
        }
        // Delete blog
        $blog = $blog->delete();
        // Broadcast deletion of blog
        BlogUpdatedEvent::dispatch($id);
        return json_encode($blog);
    }
}
?>