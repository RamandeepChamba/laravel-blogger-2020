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

        return back();
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

    public function getReplies($comment_id)
    {
        return Comment::findOrFail($comment_id)->replies()->with('user')->get();
    }

    protected function validatedData()
    {
        return request()->validate([
            'comment' => 'required|min:5',
            'blog_id' => 'required',
            'parent_id' => 'nullable'
        ]);
    }
}
