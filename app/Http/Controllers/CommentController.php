<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use App\Blog;
use App\Comment;
use App\Traits\DeleteComment;

class CommentController extends Controller
{    
    use DeleteComment;

    public function __construct()
    {
        $this->middleware('auth')->except(['getReplies']);
        $this->middleware('ajax')->only(
            ['store', 'update', 'getReplies']
        );
    }

    // Create
    public function store(Request $request)
    {
        $data = $this->validatedData();
       
        $blog = Blog::findOrFail($data['blog_id']);

        if (isset($data['parent_id'])) {
            $parentComment = Comment::findOrFail($data['parent_id']);
        }

        $comment = new Comment(['comment' => $data['comment']]);
        $comment->user()->associate($request->user());

        if(isset($parentComment)) {
            $comment['parent_id'] = $parentComment->id;
        }

        $blog->comments()->save($comment);
        return Comment::where('id', '=', $comment->id)
            ->with([
                'user:id,name', 
                'user.profile',
                'likes' => function ($query) {
                    // Don't know how to alias (likes AS userLiked)
                    if(Auth::user()) {
                        $query->where('user_id', '=', request()->user()->id);
                    }
                    else {
                        $query->where('user_id', '=', -1);
                    }
                }
            ])
            ->withCount('replies')
            ->withCount('likes')
            ->first()->toJson();
    }

    // Delete
    public function destroy($id)
    {
        return $this->deleteComment($id);
    }

    // Update
    public function update(Request $request, $id)
    {
        $data = $this->validatedData();
        $comment = Comment::where('id', '=', $id)
            ->with([
                'user:id,name', 
                'user.profile',
                'likes' => function ($query) {
                    // Don't know how to alias (likes AS userLiked)
                    if(Auth::user()) {
                        $query->where('user_id', '=', request()->user()->id);
                    }
                    else {
                        $query->where('user_id', '=', -1);
                    }
                }
            ])
            ->withCount('replies')
            ->withCount('likes')
            ->first();
        $comment->comment = $data['comment'];
        $comment->save();
        return $comment->toJson();
    }

    // Get replies
    public function getReplies($parent_id)
    {
        $parent = Comment::findOrFail($parent_id);
        $replies = $parent->replies()
            ->with([
                'user:id,name', 
                'user.profile',
                'likes' => function ($query) {
                    // Don't know how to alias (likes AS userLiked)
                    if(Auth::user()) {
                        $query->where('user_id', '=', request()->user()->id);
                    }
                    else {
                        $query->where('user_id', '=', -1);
                    }
                }
            ])
            ->withCount('replies')
            ->withCount('likes')
            ->get()->toJson();
        return $replies;
    }

    protected function validatedData()
    {
        return request()->validate([
            'comment' => 'required|min:5',
            'blog_id' => 'nullable',
            'parent_id' => 'nullable'
        ]);
    }
}
