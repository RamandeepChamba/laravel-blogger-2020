<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\Comment;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store()
    {
        $data = $this->validatedData();
        $blog = Blog::findOrFail($data['blog_id']);
        $comment = new Comment(['comment' => $data['comment']]);
        $comment['user_id'] = auth()->user()->id;
        $blog->comments()->save($comment);
        return redirect('/blogs/' . $comment->commentable->id);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        // Check if auth user has right
        if ($comment->user_id !== auth()->user()->id) {
            abort(401, "Not your comment");
        }

        $comment->delete();

        return redirect('/blogs/' . $comment->commentable->id);
    }

    protected function validatedData()
    {
        return request()->validate([
            'comment' => 'required|min:5',
            'blog_id' => 'required'
        ]);
    }
}
