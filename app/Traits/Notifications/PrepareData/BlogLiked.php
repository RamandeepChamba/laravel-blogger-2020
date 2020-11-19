<?php

namespace App\Traits\Notifications\PrepareData;

trait BlogLiked {
    function prepareData($notifiable, $blog, $liker)
    {
        $prettyBlog = (object)NULL;
        $prettyLiker = (object)NULL;
        
        $prettyBlog->id = $blog->id;
        $prettyBlog->title = $blog->title;

        $prettyLiker->id = $liker->id;
        $prettyLiker->name = $liker->name;
            
        return [
            'blog' => $prettyBlog,
            'liker' => $prettyLiker
        ];
    }
}