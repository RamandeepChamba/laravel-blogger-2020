<?php

namespace App\Traits;

use \Auth;
use App\Comment;

trait PrettyComment {
    public function prettyComment($comment_id)
    {
        return Comment::where('id', '=', $comment_id)
            ->with([
                'user:id,name', 
                'user.profile',
                'likes' => function ($query) {
                    // Don't know how to alias (likes AS userLiked)
                    if(Auth::user()) {
                        $query->where('user_id', '=', Auth::user()->id);
                    }
                    else {
                        $query->where('user_id', '=', -1);
                    }
                }
            ])
            ->withCount('replies')
            ->withCount('likes')
            ->first();
    }
}
?>