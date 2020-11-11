<?php

namespace App\Traits;

use \Auth;
use App\User;

trait FilterBlogs {
    public function filterBlogs($blogs)
    {
        $filters = (object)NULL;
        
        if($blogs->count()) {
            // Recent first
            $blogs = $blogs->toQuery()->latest()->get();
        }

        if(request('followingsOnly')) {
            $filters->followingsOnly = true;
            // Filter by followings only blogs
            $followings = User::find(Auth::id())->followings;

            if($followings->count()) {
                $followings = $followings->toQuery()
                    ->select('id')->get()->toArray();

                $followings = array_map(
                    function($user) {
                        return $user['id'];
                    },
                    $followings);

                $blogs = $blogs->toQuery()
                    ->withCount('likes')->get()->whereIn('user_id', $followings);    
            }
            else {
                $blogs = collect([]);
            }
        }

        if(request('sortBy')) {
            $filters->sortBy = request('sortBy');
            // Sort by likes count (popular)
            if(request('sortBy') === 'popular') {
                if($blogs->count()) {
                    $blogs = $blogs->toQuery()
                        ->withCount('likes')
                        ->orderBy('likes_count', 'DESC')
                        ->orderBy('created_at', 'DESC');
                }
            }
            // Sort by recent (default)
            elseif(request('sortBy') === 'latest') {
                if($blogs->count()) {
                    $blogs = $blogs->toQuery()->latest()->get();
                }
            }
            // Sort by oldest
            elseif(request('sortBy') === 'oldest') {
                if($blogs->count()) {
                    $blogs = $blogs->toQuery()->oldest()->get();
                }
            }
        }

        return [
            'blogs' => $blogs,
            'filters' => $filters
        ];
    }
}
?>